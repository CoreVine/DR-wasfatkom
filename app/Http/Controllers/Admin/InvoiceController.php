<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/
use Rap2hpoutre\FastExcel\FastExcel;
use Mpdf\Mpdf;
use Throwable;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Favorite;
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
        $this->middleware('permission:review_invoice')->only(['review_status', 'change_invoice_status' , 'change_invoice_payment']);
    }


    public function send_invoice($id)
    {




        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product', 'invoice_packages.package'])->findOrFail($id);
        $caption=  $this->caption($item->client_name , $item->invoice_num ,request());
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
            'document' =>  $link_send,
            'caption' =>  $caption
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
            return   $message_ar. "\n"."لينك دفع  \n". $request->link_payment;
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
        $reviewers  = User::where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();

        if (request('client')) {
            $data = $data->where(function ($q) {
                $q->where("client_name", "like", "%" . request('client') . "%")->orWhere("client_mobile", "like", "%" . request('client') . "%");
            });
        }

        foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {

            if (request($input)) {
                $data = $data->where($input,  request($input));
            }
        }


        if (request('from_date')) {
            $data = $data->whereDate("created_at", ">=", request('from_date'));
        }


        if (request('to_date')) {
            $data = $data->whereDate("created_at", "<=", request('to_date'));
        }





        $data = $data->paginate(config('app.paginate_number'));


        $doctor_sel = User::where("type", UserRoleEnum::Doctor->value)->where("id",  request('doctor_id'))->first()?->name;
        $review_sel = User::where("type", UserRoleEnum::Admin->value)->where("id",  request('doctor_id'))->first()?->name;


        return view("admin.invoices.index",  compact('data', 'doctors', 'reviewers', 'doctor_sel', 'review_sel'));
    }



    public function export()
    {


        $data = Invoice::with(['doctor', 'reviewer'])->withCount('invoice_items');

        if (request('client')) {
            $data = $data->where(function ($q) {
                $q->where("client_name", "like", "%" . request('client') . "%")->orWhere("client_mobile", "like", "%" . request('client') . "%");
            });
        }

        foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {

            if (request($input)) {
                $data = $data->where($input,  request($input));
            }
        }


        if (request('from_date')) {
            $data = $data->whereDate("created_at", ">=", request('from_date'));
        }


        if (request('to_date')) {
            $data = $data->whereDate("created_at", "<=", request('to_date'));
        }





        $data = $data->get();


        $export_data = [];

        foreach ($data as $item) {
            $export_data[] = [

                __('messages.Reference') => "#".$item->invoice_num,
                __('messages.Client')  => $item->client_name,
                __('messages.Phone Number') => $item->client_mobile,
                __('messages.Doctor') =>  $item->doctor->name,
                __('messages.Reviewer') =>  $item->reviewer ? $item->reviewer->name : '----' ,
                __('messages.Status') =>  __('messages.' . $item->status),
                    "Payment Type" => $item->payment_type  ?  $item->payment_type  : 'Unpaid',
                __('messages.Total')  => $item->total,
                __('messages.Date created') =>$item->created_at_format ,

                // Add other fields you want to include
            ];
        }
        return (new FastExcel(collect($export_data)))->download('invoices.xlsx');





    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $products = Product::with(["category", "sub_category"])->select("id", "price", 'category_id', 'sub_category_id', 'barcode')->get();
        $packages = Package::select("id", "price")->get();

        if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
            $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctor_ids)->get();
            if (!$doctors && $user->is_all_doctor == "0") {
                return abort(404);
            }
        } else
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->select('id', 'name', 'clinic_name')->get();


        $reviewers  = User::when(!$user->hasPermissionTo("admins"), function ($q) {
            $q->where("id", auth()->id());
        })->where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();


        $favorites = Favorite::with('product')->whereHas('product',  function ($q) {
            $q->where("is_active", true);
        })->where("doctor_id", auth()->id())->get();
        $favorites_ids = $favorites->pluck('product_id')->toArray();
        $favorites_ids_str  = implode(",", $favorites_ids);

        return view("admin.invoices.create",  compact('products', 'doctors', 'reviewers', 'favorites_ids_str', 'favorites', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {

        //
        DB::beginTransaction();
        try {

            if (!$request->packages && !$request->items) {
                return ResponseHelper::sendResponseError(null,  Response::HTTP_BAD_REQUEST, __("messages_301.Please add at least product or package"));
            }

            $invoice_num =  $this->get_invoice_num();
            $data = [
                'doctor_id' => $request->doctor_id,
                'invoice_num' =>   $invoice_num ,
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
                'payment_order_id'=>time().  $invoice_num
            ];


            $data['doctor_commission'] =  auth()->user()->type->value == UserRoleEnum::Admin->value ? $request->doctor_commission : Setting::where('key', 'general_account_doctors_commission')->first()->value;



            $invoice = Invoice::create($data);


            $item_data = $this->get_item_data($request, $invoice->id);

            InvoiceItem::insert($item_data['data']);

            if ($request->packages && count($request->packages)) {


                $package_data = $this->get_package_data($request, $invoice->id);
                InvoicePackage::insert($package_data['data']);


                $invoice_total = $item_data['invoice_total'] + $package_data['invoice_total'];
               // $total_after_tax = $this->calc_tax( $invoice_total );
                $invoice->update([
                    'sub_total' => $item_data['invoice_sub_total']  + $package_data['invoice_total'],
                    'total' => $invoice_total,
                    'tax'=>0,
                    'tax_value'=>0,
                    'discount' => $item_data['invoice_total_discount'],
                ]);
            } else{

               // $total_after_tax = $this->calc_tax($item_data['invoice_total']);

                $invoice->update([
                    'sub_total' => $item_data['invoice_sub_total'],
                    'total' => $item_data['invoice_total'],
                    'tax'=>0,
                    'tax_value'=>0,
                    'discount' => $item_data['invoice_total_discount'],
                ]);
            }


            $this->calc_tax($invoice->id);




            DB::commit();
            $doctor_commission_value = HelperApp::calac_commission($invoice);
            $invoice->doctor_commission_value = $doctor_commission_value;
            $invoice->save();
            Alert::toast(__("messages.done successfully"), "success");
            return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.create')]);
        } catch (Throwable $e) {


            DB::rollBack();


            HelperApp::set_log_catch("Store invoice", $e->getMessage());

            return ResponseHelper::sendResponseSuccess(null , Response::HTTP_BAD_REQUEST , __("messages.An error occurred in data entry"));

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product', 'invoice_packages.package'])->findOrFail($id);
        HelperApp::make_qr_code($item->id);
        return view('admin.invoices.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $item = Invoice::with(['doctor', 'invoice_items'])->findOrFail($id);
        $packages = Package::select("id", "price")->get();

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
        $reviewers  = User::when(!$user->hasPermissionTo("admins"), function ($q) {
            $q->where("id", auth()->id());
        })->where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();

        $favorites = Favorite::with('product')->whereHas('product',  function ($q) {
            $q->where("is_active", true);
        })->where("doctor_id", auth()->id())->get();
        $favorites_ids = $favorites->pluck('product_id')->toArray();
        $favorites_ids_str  = implode(",", $favorites_ids);


        if ($item->status == OrderStatusEnum::Paid->value) {
            Alert::toast(__("messages_301.Cannot edit a paid invoice"), 'error');
            return back();
        }
        return view('admin.invoices.edit', compact('item', 'doctors', 'products', 'reviewers', 'favorites_ids_str', 'favorites', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request, string $id)
    {
        //
        DB::beginTransaction();
        try {

            if (!$request->packages && !$request->items) {
                return ResponseHelper::sendResponseError(null,  Response::HTTP_BAD_REQUEST, __("messages_301.Please add at least product or package"));
            }

            $invoice = Invoice::with(['doctor', 'invoice_items'])->findOrFail($id);
            if ($invoice->status == OrderStatusEnum::Paid->value) {
                Alert::toast(__("messages_301.Cannot edit a paid invoice"), 'error');
                return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.index')]);
            }



            $data = [
                'doctor_id' => $request->doctor_id,
                'review_id' => $request->review_id ?: null,
                'client_name' => $request->client_name,
                'client_mobile' => $request->client_mobile,
                'client_location' => $request->client_location,
                'coupon_id' => $request->coupon_id ?: null,
                'notes' => $request->notes,
            ];
            if (auth()->user()->type->value == UserRoleEnum::Doctor->value && $invoice->status == OrderStatusEnum::Done->value)
                $data['status'] = OrderStatusEnum::Draft->value;

            $data['doctor_commission'] =  auth()->user()->type->value == UserRoleEnum::Admin->value ? $request->doctor_commission : $invoice->doctor_commission;
            $data['review_id'] =  auth()->user()->type->value == UserRoleEnum::Admin->value ? $request->review_id : $invoice->review_id;


            $item_data = $this->get_item_data_update($request, $invoice->id);

            if ($request->packages) {
                $package_data = $this->get_package_data($request, $invoice->id);

                $invoice_total = $item_data['invoice_total'] + $package_data['invoice_total'];
               // $total_after_tax = $this->calc_tax( $invoice_total  ,  $invoice->tax_value );



                $data['total'] =   $invoice_total;
                $data['tax'] =  0;
                $data['tax_value'] = 0;
                $data['sub_total'] = $item_data['invoice_sub_total'] + $package_data['invoice_total'];
                $data['discount'] = $item_data['invoice_total_discount'];
                InvoicePackage::where('invoice_id', $invoice->id)->delete();
                InvoicePackage::insert($package_data['data']);
            } else {
              //  $total_after_tax = $this->calc_tax( $item_data['invoice_total']  ,  $invoice->tax_value );



                $data['total'] =  $item_data['invoice_total'] ;
                $data['tax'] = 0;
                $data['tax_value'] = 0;

                $data['sub_total'] = $item_data['invoice_sub_total'];
                $data['discount'] = $item_data['invoice_total_discount'];
            }
            $invoice->update($data);


            InvoiceItem::where('invoice_id', $invoice->id)->delete();
            InvoiceItem::insert($item_data['data']);




            DB::commit();
            $doctor_commission_value = HelperApp::calac_commission($invoice);
            $invoice->doctor_commission_value = $doctor_commission_value;
            $invoice->save();

            $this->calc_tax($invoice->id);


            Alert::toast(__("messages.done successfully"), "success");
            return ResponseHelper::sendResponseSuccess(["url" => route('admin.invoices.edit', $invoice->id)]);
        } catch (Throwable $e) {

            DB::rollBack();

            HelperApp::set_log_catch("update invoice", $e->getMessage());

            Alert::toast(__("messages.An error occurred in data entry"), "error");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            if (!$product)
                abort(404);
            $total_before_discount = $product->price * $item['qty'];
            $total = $product->price * $item['qty'];
            if ($user->type->value == UserRoleEnum::Admin->value && $item['discount'] != 0) {
                $total = $total - ($total * $item['discount']) / 100;
            }
            $data[] = [
                'invoice_id' => $invoice,
                'product_id' => $item['product_id'],
                'price' => $product->price,
                'qty' => $item['qty'],
                'total_befor_discount' => $total_before_discount,
                'total' => $total,
                'the_use' => $item['the_use'],
                'discount' => $user->type->value == UserRoleEnum::Admin->value ? $discount_item : 0,

            ];

            $invoice_total = $invoice_total + $total;
            $invoice_sub_total = $invoice_sub_total + $total_before_discount;
        }
        $invoice_total_discount =   $invoice_sub_total - $invoice_total;
        return ['data' => $data, 'invoice_total' => $invoice_total, 'invoice_sub_total' => $invoice_sub_total, 'invoice_total_discount' => $invoice_total_discount];
    }


    private function get_package_data($request, $invoice)
    {
        $data = [];
        $invoice_total = 0;
        foreach ($request->packages as $item) {
            if (isset($item['package_id']) && $item['package_id']) {
                $package = Package::where('id', $item['package_id'])->select('price')->first();
                if (!$package)
                    abort(404);
                $total = $package->price * $item['qty'];

                $data[] = [
                    'invoice_id' => $invoice,
                    'package_id' => $item['package_id'],
                    'price' => $package->price,
                    'qty' => $item['qty'],
                    'total' => $total,

                ];

                $invoice_total = $invoice_total + $total;
            }
        }
        return ['data' => $data, 'invoice_total' => $invoice_total];
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
        $last_invoice  = DB::table('invoices')->orderBy('id', 'DESC')->first();


      //  $last_invoice  = Invoice::orderBy('id', 'DESC')->first();
        $invoice_num = 1001;
        if ($last_invoice) {
            $invoice_num = $last_invoice->invoice_num + 1;
        }

        return $invoice_num;
    }

    public function review(Request $request,  $id)
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


    public function cancel_status($id)
    {
        $item = Invoice::findOrFail($id);
        $item->status = OrderStatusEnum::Cancel->value;
        $item->save();

        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }


    public function change_invoice_status()
    {
        $invoice = Invoice::findOrFail(request('id'));
        if (!in_array(request('new_value'), OrderStatusEnum::values())) {
            return response(null,  404);
        }

        $color_old = HelperApp::get_color_status($invoice->status);
        $invoice->status = request('new_value');
        $invoice->save();

        return response()->json([
            "color_old" =>   $color_old,
            "color" => HelperApp::get_color_status(request('new_value')),
            "value" => __("messages." . request('new_value'))
        ]);
    }

    public function change_invoice_payment()
    {
        $invoice = Invoice::findOrFail(request('id'));
        if (!in_array(request('new_value'), PaymentTypeEnum::values())) {
            return response(null,  404);
        }


        $invoice->payment_type = request('new_value');
        $invoice->save();

        return response()->json([
            //"color_old" =>   $color_old,
            //"color" => HelperApp::get_color_status(request('new_value')),
            "value" =>request('new_value')
        ]);
    }


    public function pdf($id)
    {

        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product', 'invoice_packages.package'])->findOrFail($id);
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


    public function calc_tax($invoice_id){

        $invoice = Invoice::with('invoice_items.product.category')->where("id" , $invoice_id)->first();

        $invoice_tax = 0;
        $total_price_tax=0;


        foreach($invoice->invoice_items as $item){
            if($item->product->category_id != 2){
                $total_price_tax += $item->total;
            }
        }


        $invoice_tax =  $total_price_tax * ( 15 / 100);



        $invoice->update([
            "tax"=>"15",
            "tax_value"=>$invoice_tax
        ]);





    }
}
