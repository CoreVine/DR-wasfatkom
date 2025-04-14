<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Exports\InvoicesExport;
use App\Models\Formulation;
use App\Models\InvoiceFormulation;
use Maatwebsite\Excel\Facades\Excel;

use Mpdf\Mpdf;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Favorite;
use App\Models\Coupon;
use App\Enums\UserRoleEnum;
use App\Models\AdminDoctor;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Models\InvoicePackage;
use App\Http\Helpers\HelperApp;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Helpers\HelperSetting;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:show_invoice')->only(['index', 'export', 'show']);
    $this->middleware('permission:create_invoice')->only(['create', 'store']);
    $this->middleware('permission:edit_invoice')->only(['edit', 'update']);
    // $this->middleware('permission:delete_invoice')->only('destroy');
    $this->middleware('permission:send_invoice')->only('send_status');
    $this->middleware('permission:cancel_invoice')->only('cancel_status');
    $this->middleware('permission:review_invoice')->only(['review_status', 'change_invoice_status', 'change_invoice_payment']);
  }


  public function send_invoice($id)
  {
    $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product', 'invoice_packages.package'])->findOrFail($id);
    $caption = $this->caption($item->client_name, $item->invoice_num, request());
    $html = view('admin.invoices.pdf', compact('item'))->render();

    $mpdf = new Mpdf([
      'default_font' => 'amiri', // استخدام خط يدعم اللغة العربية
    ]);
    $mpdf->SetHTMLFooter('
        <div style="font-weight: bold; font-size: 8pt; font-style: italic;">
            Chapter 2
        </div>', 'E');
    $mpdf->WriteHTML($html);
    $name_save = "uploads/invoices/" . time() . uniqid() . $item->invoice_num . ".pdf";

    $mpdf->Output($name_save, 'F');

    $link_send = asset($name_save);

    $response = Http::post('https://api.ultramsg.com/instance91009/messages/document', [
      'token' => 'd1s7099h157cqry7',
      'to' => $item->client_mobile,
      'filename' => $item->invoice_num . ".pdf",
      'document' => $link_send,
      'caption' => $caption
    ]);


    if ($response->ok()) {

      if (isset($response->json()['error'])) {
        if (is_string($response->json()['error'])) {
          Alert::toast($response->json()['error'], "error");
          return back();
        } else {
          foreach ($response->json()['error'][0] as $key => $val) {
            Alert::toast($val, "error");
            return back();
          }
        }
      } else {
        Alert::toast(__("messages.done successfully"), "success");
        return back();
      }
    } else {
      Log::info($response->json());
      Alert::toast(__("messages.An error occurred in data entry"), "error");
      return back();
    }
  }


  private function caption($client_name, $invoice_num, $request)
  {
    $coupon = $request->coupon;
    $message_ar = "

        مرحبا, عميلنا العزيز.
        " . $client_name . "  \n
لديك وصفة 📝 :
توصيل 🚚 من 2 إلى 4 ساعات:  \n
استمتع بالمميزات التاليه💪:  \n
1- الحصول على النقاط.  \n
2- خصم على وصفتك.  \n
3-إمكانية تقسيط الوصفة.  \n
4- التوصيل الي باب المنزل.  \n
5- عدم تبديل منتجات الوصفه.  \n
وصفتك إلى باب منزلك

        ";


    $message_en = "
        Hello, dear customer.  " . $client_name . "\n
        You have a prescription 📝 : " . $invoice_num . "\n


        Delivery 🚚 within 2 to 4 hours:\n\n\n

        Enjoy the following benefits 💪:\n
        1. Earning points.\n
        2. Discount on your prescription.\n
        3. Option to pay in installments.\n
        4. Home delivery.\n
        5. No product substitution\n\n

        Your prescription to your doorstep
    ";

    if ($coupon) {
      $message_ar = "

            مرحبا, عميلنا العزيز.
            " . $client_name . "\n
            لديك وصفة 📝 :
            يمكنك استخدام كود الخصم ( " . $coupon . " ) عند السداد من هذا الرابط.\n
            توصيل 🚚 من 2 إلى 4 ساعات:\n
            استمتع بالمميزات التاليه💪:\n
            1- الحصول على النقاط.\n
            2- خصم على وصفتك.\n
            3-إمكانية تقسيط الوصفة.\n
            4- التوصيل الي باب المنزل.\n
            5- عدم تبديل منتجات الوصفه.\n
                وصفتك إلى باب منزلك

                    ";


      $message_en = "
                    Hello, dear customer.  " . $client_name . "\n
                    You have a prescription 📝 : " . $invoice_num . "\n

                    You can use the discount code (" . $coupon . ") at checkout from this link.\n


                    Delivery 🚚 within 2 to 4 hours:\n\n\n

                    Enjoy the following benefits 💪:\n
                    1. Earning points.\n
                    2. Discount on your prescription.\n
                    3. Option to pay in installments.\n
                    4. Home delivery.\n
                    5. No product substitution\n

                    Your prescription to your doorstep\n
                ";
    }

    // if(app()->getLocale() == "ar"){
    return $message_ar . "\n" . "لينك دفع  \n" . $request->link_payment;
    // }else{
    //     return $message_en. "\n"."payment url \n ". $request->link_payment;
    // }


  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = Invoice::with(['doctor', 'reviewer'])->withCount('invoice_items');
    $doctors = User::where("type", UserRoleEnum::Doctor->value)->select("id", "name", "email")->get();
    $reviewers = User::where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();

    if (request('client')) {
      $data = $data->where(function ($q) {
        $q->where("client_name", "like", "%" . request('client') . "%")->orWhere("client_mobile", "like", "%" . request('client') . "%");
      });
    }

    foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {

      if (request($input)) {
        $data = $data->where($input, request($input));
      }
    }


    if (request('from_date')) {
      $data = $data->whereDate("created_at", ">=", request('from_date'));
    }

    if (request('to_date')) {
      $data = $data->whereDate("created_at", "<=", request('to_date'));
    }

    $data = $data->paginate(config('app.paginate_number'));

    $doctor_sel = User::where("type", UserRoleEnum::Doctor->value)->where("id", request('doctor_id'))->first()?->name;
    $review_sel = User::where("type", UserRoleEnum::Admin->value)->where("id", request('doctor_id'))->first()?->name;


    return view("admin.invoices.index", compact('data', 'doctors', 'reviewers', 'doctor_sel', 'review_sel'));
  }


  /*  public function export()
  {

    $data = Invoice::with(['doctor', 'reviewer'])->withCount('invoice_items');

    if (request('client')) {
      $data = $data->where(function ($q) {
        $q->where("client_name", "like", "%" . request('client') . "%")
          ->orWhere("client_mobile", "like", "%" . request('client') . "%");
      });
    }

    foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {
      if (request($input)) {
        $data = $data->where($input, request($input));
      }
    }

    if (request('from_date')) {
      $data = $data->whereDate("created_at", ">=", request('from_date'));
    }

    if (request('to_date')) {
      $data = $data->whereDate("created_at", "<=", request('to_date'));
    }

    $data = $data->get();

    $export_data = $data->map(function ($item) {
      return [
        __('messages.Reference') => "#" . $item->invoice_num,
        __('messages.Client') => $item->client_name,
        __('messages.Phone Number') => $item->client_mobile,
        __('messages.Doctor') => $item->doctor->name,
        __('messages.Reviewer') => $item->reviewer ? $item->reviewer->name : '----',
        __('messages.Status') => __('messages.' . $item->status),
        "Payment Type" => $item->payment_type ?: 'Unpaid',
        __('messages.Total') => $item->total,
        __('messages.Date created') => $item->created_at->format('Y-m-d H:i:s'),
      ];
    });

    return response()->streamDownload(function () use ($export_data) {
      (new FastExcel($export_data))->export('php://output');
    }, 'invoices.xlsx', [
      'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'Content-Disposition' => 'attachment; filename="invoices.xlsx"',
    ]);
  } */


  public function export(Request $request)
  {
    return Excel::download(new InvoicesExport($request), 'invoices.xlsx');
  }

  public function create()
  {
    $user_id = auth()->id();
    $user = User::findOrFail($user_id);
    $products = Product::with(["category", "sub_category"])->select("id", "price", 'category_id', 'sub_category_id', 'barcode')->get();
    $packages = Package::select("id", "price")->get();
    $formulations = Formulation::select("id", "price")->get();

    $coupons = Coupon::where(
      [
        ['is_active', true],
        ['count_use', '!=', 1],
        ['from_date', '<=', now()],
        ['to_date', '>=', now()]
      ]
    )->select("id", "code", "count_use", 'from_date', 'to_date')->get();

    if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
      $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
      $doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctor_ids)->get();
      if (!$doctors && $user->is_all_doctor == "0") {
        return abort(404);
      }
    } else
      $doctors = User::where('type', UserRoleEnum::Doctor->value)->select('id', 'name', 'clinic_name')->get();

    $reviewers = User::when(!$user->hasPermissionTo("admins"), function ($q) {
      $q->where("id", auth()->id());
    })->where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();


    $favorites = Favorite::with('product')->whereHas('product', function ($q) {
      $q->where("is_active", true);
    })->where("doctor_id", auth()->id())->get();
    $favorites_ids = $favorites->pluck('product_id')->toArray();
    $favorites_ids_str = implode(",", $favorites_ids);

    return view("admin.invoices.create", compact('products', 'coupons', 'doctors', 'reviewers', 'formulations', 'favorites_ids_str', 'favorites', 'packages'));
  }

  public function store(InvoiceRequest $request)
  {

    if (!$request->packages && !$request->items) {
      return ResponseHelper::sendResponseError(null, Response::HTTP_BAD_REQUEST, __("messages_301.Please add at least product or package"));
    }

    $total = 0;
    $sub_total = 0;
    $total_discount = 0;
    $items_discount = 0;

    $discount_overall = $request->input('overall_discount', 0);

    $invoice_num = $this->get_invoice_num();
    $data = [
      'doctor_id' => $request->doctor_id,
      'invoice_num' => $invoice_num,
      'status' => OrderStatusEnum::Draft->value,
      'review_id' => $request->review_id ?: null,
      'client_name' => $request->client_name,
      'client_location' => $request->client_location,
      'client_mobile' => $request->client_mobile,
      'coupon_id' => $request->coupon_id ?: null,
      'notes' => $request->notes,
      'sub_total' => 0,
      'discount' => 0,
      'total' => 0,
      'payment_order_id' => time() . $invoice_num,
    ];

    $data['doctor_commission'] = auth()->user()->type->value == UserRoleEnum::Admin->value
      ? $request->doctor_commission
      : Setting::where('key', 'general_account_doctors_commission')->first()->value;

    $invoice = Invoice::create($data);

    HelperApp::make_qr_code($invoice->id);

    // Process items
    if ($request->items) {
      $item_data = $this->get_item_data($request, $invoice->id);
      $total += $item_data['invoice_total'];
      $sub_total += $item_data['invoice_sub_total'];
      $total_discount += $item_data['invoice_total_discount'];
      $items_discount += $item_data['invoice_total_discount'] ?? 0;
      InvoiceItem::insert($item_data['data']);
    }

    // Process packages
    if ($request->packages && count($request->packages)) {
      $package_data = $this->get_package_data($request, $invoice->id);
      $total += $package_data['invoice_total'];
      $sub_total += $package_data['invoice_total'];
      InvoicePackage::insert($package_data['data']);
    }

    // Process formulations
    if ($request->formulations && count($request->formulations) > 0) {
      $formulation_data = $this->get_formulation_data($request, $invoice->id);
      $total += $formulation_data['invoice_total'];
      $sub_total += $formulation_data['invoice_sub_total'];
      $total_discount += $formulation_data['invoice_total_discount'] ?? 0;
      $items_discount += $formulation_data['invoice_total_discount'] ?? 0;
      InvoiceFormulation::insert($formulation_data['data']);
    }

    // Calculate final discount
    $overall_discount_value = ($sub_total - $total_discount) * ($discount_overall / 100);
    $final_total = $sub_total - $total_discount - $overall_discount_value;
    $coupon = Coupon::where('is_active', 1)->where('id', $request->coupon_id)->first();

    if ($coupon) {
      $coupon->count_use = $coupon->count_use - 1;
      $coupon->save();

      $couponPercentage = $coupon->percentage;
      $couponValue = $final_total * ($couponPercentage / 100);
      $final_total -= $couponValue;
    }

    $invoice->update([
      'sub_total' => $sub_total,
      'total' => $final_total,
      'discount' => $discount_overall ?? 0,
      'coupon_percentage' => $couponPercentage ?? 0,
      'coupon_discount' => $couponValue ?? 0,
      'items_discount' => $items_discount ?? 0,
      'overall_discount' => $overall_discount_value ?? 0,
      'overall_percentage' => $discount_overall ?? 0,
      'tax' => 0,
      'tax_value' => 0,
    ]);

    $this->calc_tax($invoice->id);

    DB::commit();
    $doctor_commission_value = HelperApp::calac_commission($invoice);
    $invoice->doctor_commission_value = $doctor_commission_value;
    $invoice->save();

    Alert::toast(__("messages.done successfully"), "success");
    return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.create')]);
  }

  public function show(string $id)
  {
    $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product', 'invoice_packages.package'])->findOrFail($id);
    HelperApp::make_qr_code($item->id);
    return view('admin.invoices.show', compact('item'));
  }

  public function edit(string $id)
  {

    $user_id = auth()->id();
    $user = User::findOrFail($user_id);
    $item = Invoice::with(['doctor', 'invoice_items', 'invoice_packages', 'invoice_formulations'])->findOrFail($id);
    $packages = Package::select("id", "price")->get();
    $formulations = Formulation::select("id", "price")->get();
    $coupons = Coupon::where(
      [
        ['is_active', true],
        ['count_use', '!=', 1],
        ['from_date', '<=', now()],
        ['to_date', '>=', now()]
      ]
    )->select("id", "code", "count_use", 'from_date', 'to_date')->get();

    $items_discount_percentage = $item->invoice_items->sum(function ($i) {
      return ($i->price * ($i->discount / 100)) * $i->qty;
    });

    $formulations_discount_percentage = $item->invoice_formulations->sum(function ($f) {
      return ($f->price * ($f->discount / 100)) * $f->qty;
    });

    $all_discounts = round($items_discount_percentage + $formulations_discount_percentage, 2);

    if (($user->type->value == UserRoleEnum::Doctor->value && ($item->doctor_id != $user_id))) {
      return abort(404);
    } elseif ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
      $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
      $doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctor_ids)->get();
      if (!$doctors && $user->is_all_doctor == "0") {
        return abort(404);
      }
    } else
      $doctors = User::where('type', UserRoleEnum::Doctor->value)->select('id', 'name', 'clinic_name')->get();
    $products = Product::with(["category", "sub_category"])->select("id", "price", 'category_id', 'sub_category_id', "barcode")->get();
    $reviewers = User::when(!$user->hasPermissionTo("admins"), function ($q) {
      $q->where("id", auth()->id());
    })->where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();

    $favorites = Favorite::with('product')->whereHas('product', function ($q) {
      $q->where("is_active", true);
    })->where("doctor_id", auth()->id())->get();
    $favorites_ids = $favorites->pluck('product_id')->toArray();
    $favorites_ids_str = implode(",", $favorites_ids);


    if ($item->status == OrderStatusEnum::Paid->value) {
      Alert::toast(__("messages_301.Cannot edit a paid invoice"), 'error');
      return back();
    }
    return view('admin.invoices.edit', compact('item', 'all_discounts', 'coupons', 'formulations', 'doctors', 'products', 'reviewers', 'favorites_ids_str', 'favorites', 'packages'));
  }

  public function update(InvoiceRequest $request, string $id)
  {
    if (!$request->packages && !$request->items) {
      return ResponseHelper::sendResponseError(
        null,
        Response::HTTP_BAD_REQUEST,
        __("messages_301.Please add at least product or package")
      );
    }

    $invoice = Invoice::findOrFail($id);

    if ($invoice->status == OrderStatusEnum::Paid->value) {
      Alert::toast(__("messages_301.Cannot edit a paid invoice"), 'error');
      return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.index')]);
    }

    $total = 0;
    $sub_total = 0;
    $total_discount = 0;
    $items_discount = 0;

    $discount_overall = $request->input('overall_discount') ?? 0;

    $data = [
      'doctor_id' => $request->doctor_id,
      'review_id' => $request->review_id ?: null,
      'client_name' => $request->client_name,
      'client_location' => $request->client_location,
      'client_mobile' => $request->client_mobile,
      'coupon_id' => $request->coupon_id ?: null,
      'notes' => $request->notes,

    ];

    $data['doctor_commission'] = auth()->user()->type->value == UserRoleEnum::Admin->value
      ? $request->doctor_commission
      : Setting::where('key', 'general_account_doctors_commission')->first()->value;

    // Update basic invoice data
    $invoice->update($data);

    // Clear previous items/packages/formulations
    InvoiceItem::where('invoice_id', $invoice->id)->delete();
    InvoicePackage::where('invoice_id', $invoice->id)->delete();
    InvoiceFormulation::where('invoice_id', $invoice->id)->delete();

    // Process items
    if ($request->items) {
      $item_data = $this->get_item_data($request, $invoice->id);
      $total += $item_data['invoice_total'];
      $sub_total += $item_data['invoice_sub_total'];
      $total_discount += $item_data['invoice_total_discount'] ?? 0;
      $items_discount += $item_data['invoice_total_discount'] ?? 0;
      InvoiceItem::insert($item_data['data']);
    }

    // Process packages
    if ($request->packages && count($request->packages)) {
      $package_data = $this->get_package_data($request, $invoice->id);
      $total += $package_data['invoice_total'];
      $sub_total += $package_data['invoice_total'];
      InvoicePackage::insert($package_data['data']);
    }

    // Process formulations
    if ($request->formulations && count($request->formulations) > 0) {
      $formulation_data = $this->get_formulation_data($request, $invoice->id);
      $total += $formulation_data['invoice_total'];
      $sub_total += $formulation_data['invoice_sub_total'];
      $total_discount += $formulation_data['invoice_total_discount'] ?? 0;
      $items_discount += $formulation_data['invoice_total_discount'] ?? 0;
      InvoiceFormulation::insert($formulation_data['data']);
    }

    // Calculate discounts and totals
    $overall_discount_value = ($sub_total - $total_discount) * ($discount_overall / 100);
    $final_total = $sub_total - $total_discount - $overall_discount_value;

    // Apply coupon discount if applicable
    $coupon = Coupon::where('is_active', 1)->where('id', $request->coupon_id)->first();

    if ($coupon) {
      $coupon->count_use = $coupon->count_use - 1;
      $coupon->save();

      $couponPercentage = $coupon->percentage;
      $couponValue = $final_total * ($couponPercentage / 100);
      $final_total -= $couponValue;
    }

    // Update the invoice with calculated totals, discounts, and coupon details
    $invoice->update([
      'sub_total' => $sub_total,
      'total' => $final_total,
      'discount' => $discount_overall,
      'coupon_percentage' => $couponPercentage ?? 0,
      'coupon_discount' => $couponValue ?? 0,
      'items_discount' => $items_discount,
      'overall_discount' => $overall_discount_value,
      'overall_percentage' => $discount_overall,
      'tax' => 0,
      'tax_value' => 0,
    ]);

    $this->calc_tax($invoice->id);

    // Commission calculation
    $doctor_commission_value = HelperApp::calac_commission($invoice);
    $invoice->doctor_commission_value = $doctor_commission_value;
    $invoice->save();

    DB::commit();

    Alert::toast(__("messages.done successfully"), "success");
    return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.edit', $invoice->id)]);
  }

  private function get_item_data($request, $invoice)
  {
    $data = [];
    $user = auth()->user();
    $invoice_total = 0;
    $invoice_total_discount = 0;
    $invoice_sub_total = 0;
    foreach ($request->items as $item) {

      $discount_item = $item['discount'];
      if (!$discount_item) {
        $discount_item =  0;
      }
      $product = Product::where('id', $item['product_id'])->active()->select('price')->first();
      if (!$product) abort(404);

      $total_before_discount = $product->price * $item['qty'];
      $total = $product->price * $item['qty'];
      if ($user->type->value == UserRoleEnum::Admin->value && $item['discount'] != 0) {
        $total = $total - ($total * $item['discount']) / 100;
      }
      $data[] = [
        'invoice_id' => $invoice,
        'product_id' => $item['product_id'],
        'price' => $product->price,
        'qty' => $item['qty'] ?? 1,
        'total_befor_discount' => $total_before_discount,
        'total' => $total,
        'the_use' => $item['the_use'],
        'discount' => $user->type->value == UserRoleEnum::Admin->value ? $discount_item ?? 0 : 0,

      ];

      $invoice_total = $invoice_total + $total;
      $invoice_sub_total = $invoice_sub_total + $total_before_discount;
    }
    $invoice_total_discount = $invoice_sub_total - $invoice_total;
    return ['data' => $data, 'invoice_total' => $invoice_total, 'invoice_sub_total' => $invoice_sub_total, 'invoice_total_discount' => $invoice_total_discount];
  }

  private function get_package_data($request, $invoice)
  {
    $data = [];
    $invoice_total = 0;
    foreach ($request->packages as $item) {
      if (isset($item['package_id']) && $item['package_id']) {
        $package = Package::where('id', $item['package_id'])->select('price')->first();
        if (!$package) abort(404);
        $total = $package->price * $item['qty'];

        $data[] = [
          'invoice_id' => $invoice,
          'package_id' => $item['package_id'],
          'price' => $package->price,
          'qty' => $item['qty'] ?? 1,
          'total' => $total,

        ];

        $invoice_total = $invoice_total + $total;
      }
    }
    return ['data' => $data, 'invoice_total' => $invoice_total];
  }

  private function get_formulation_data($request, $invoice)
  {
    $data = [];
    $user = auth()->user();
    $invoice_total = 0;
    $invoice_total_discount = 0;
    $invoice_sub_total = 0;

    foreach ($request->formulations as $item) {
      $discount_item = $item['discount'] ?? 0;

      $product = Formulation::where('id', $item['formulation_id'])->select('price')->first();
      if (!$product) abort(404);

      $total_before_discount = $product->price * $item['qty'];
      $total = $total_before_discount;

      if ($user->type->value == UserRoleEnum::Admin->value && $discount_item != 0) {
        $total -= ($total * $discount_item) / 100;
      }

      $data[] = [
        'invoice_id' => $invoice,
        'formulation_id' => $item['formulation_id'],
        'price' => $product->price,
        'qty' => $item['qty'] ?? 1,
        'total_befor_discount' => $total_before_discount,
        'total' => $total,
        'discount' => $user->type->value == UserRoleEnum::Admin->value ? $discount_item ?? 0 : 0,
      ];

      $invoice_total += $total;
      $invoice_sub_total += $total_before_discount;
    }

    $invoice_total_discount = $invoice_sub_total - $invoice_total;

    return [
      'data' => $data,
      'invoice_total' => $invoice_total,
      'invoice_sub_total' => $invoice_sub_total,
      'invoice_total_discount' => $invoice_total_discount
    ];
  }

  private function get_item_data_update($request, $invoice)
  {
    $data = [];
    $package_data = [];
    $user = auth()->user();
    $invoice_total = 0;
    $invoice_total_discount = 0;
    $invoice_sub_total = 0;
    $item_ids = InvoiceItem::where('invoice_id', $invoice)->pluck('discount', 'product_id')->toArray();
    foreach ($request->items as $item) {

      $product = Product::where('id', $item['product_id'])->active()->select('price')->first();
      if (!$product)
        abort(404);

      $total_before_discount = $product->price * $item['qty'];
      $total = $product->price * $item['qty'];
      $item_discount = 0;

      if ($user->type->value == UserRoleEnum::Admin->value) {
        $item_discount =  $item['discount'];
        $total = $total - ($total * $item['discount']) / 100;
      } else {
        if (isset($item_ids[$item['product_id']])) {

          $item_discount = $item_ids[$item['product_id']];
          $total = $total - ($total *  $item_ids[$item['product_id']]) / 100;
        }
      }
      $data[] = [
        'invoice_id' => $invoice,
        'product_id' => $item['product_id'],
        'price' => $product->price,
        'qty' => $item['qty'],
        'total_befor_discount' => $total_before_discount,
        'total' => $total,
        'the_use' => $item['the_use'],
        'discount' => $item_discount
      ];


      $invoice_total = $invoice_total + $total;
      $invoice_sub_total = $invoice_sub_total + $total_before_discount;
    }
    $invoice_total_discount =   $invoice_sub_total - $invoice_total;
    return ['data' => $data, 'invoice_total' => $invoice_total, 'invoice_sub_total' => $invoice_sub_total, 'invoice_total_discount' => $invoice_total_discount];
  }

  private function get_invoice_num()
  {
    $last_invoice = DB::table('invoices')->orderBy('id', 'DESC')->first();

    //  $last_invoice  = Invoice::orderBy('id', 'DESC')->first();
    $invoice_num = 1001;
    if ($last_invoice) {
      $invoice_num = $last_invoice->invoice_num + 1;
    }

    return $invoice_num;
  }

  public function review(Request $request, $id)
  {
    $invoice = Invoice::whereNotNull("review_id")->whereNotIn("status", [OrderStatusEnum::Paid->value, OrderStatusEnum::Done->value])->findOrFail($id);
    $invoice->status = OrderStatusEnum::Done->value;
    $invoice->save();

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function send_status($id)
  {
    $item = Invoice::findOrFail($id);
    $item->status = OrderStatusEnum::Send->value;
    $item->save();

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function under_delivery($id)
  {
    $item = Invoice::findOrFail($id);
    $item->status = OrderStatusEnum::UnderDelivery->value;
    $item->save();

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function cancel_status(Request $request, $id)
  {
    $item = Invoice::findOrFail($id);
    $item->$item->status = OrderStatusEnum::Cancel->value;
    $item->save();

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function change_invoice_status()
  {
    $invoice = Invoice::findOrFail(request('id'));
    if (!in_array(request('new_value'), OrderStatusEnum::values())) {
      return response(null, 404);
    }

    if (request('new_value') == 'cancel') {
      $invoice->cancel_reason = request('cancel_reason');
    }

    $color_old = HelperApp::get_color_status($invoice->status);
    $invoice->status = request('new_value');
    $invoice->save();

    return response()->json([
      "color_old" => $color_old,
      "color" => HelperApp::get_color_status(request('new_value')),
      "value" => __("messages." . request('new_value'))
    ]);
  }

  public function change_invoice_payment()
  {
    $invoice = Invoice::findOrFail(request('id'));
    if (!in_array(request('new_value'), PaymentTypeEnum::values())) {
      return response(null, 404);
    }


    $invoice->payment_type = request('new_value');
    $invoice->save();

    return response()->json([
      //"color_old" =>   $color_old,
      //"color" => HelperApp::get_color_status(request('new_value')),
      "value" => request('new_value')
    ]);
  }

  public function pdf($id)
  {

    $item = Invoice::with(['doctor', 'coupon', 'reviewer', 'invoice_items.product', 'invoice_packages.package', 'invoice_formulations.formulation'])->findOrFail($id);
    HelperApp::make_qr_code($item->id);
    $html = view('admin.invoices.pdf', compact('item'))->render() . "<br></br>";

    $mpdf = new Mpdf([
      'default_font' => 'amiri', // استخدام خط يدعم اللغة العربية
    ]);
    $mpdf->SetHTMLFooter('
        <div style="font-weight: bold; font-size: 8pt; font-style: italic;">
            Chapter 2
        </div>', 'E');
    $mpdf->WriteHTML($html);
    $mpdf->Output('my_pdf.pdf', 'I');
  }

  public function calc_tax($invoice_id)
  {

    $invoice = Invoice::with('invoice_items.product.category')->where("id", $invoice_id)->first();

    $invoice_tax = 0;
    $total_price_tax = 0;


    foreach ($invoice->invoice_items as $item) {
      if ($item->product->category_id != 2) {
        $total_price_tax += $item->total;
      }
    }

    $invoice_tax = $total_price_tax * (15 / 100);

    $invoice->update([
      "tax" => "15",
      "tax_value" => $invoice_tax
    ]);
  }
}
