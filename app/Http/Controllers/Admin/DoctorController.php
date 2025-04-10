<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Enums\OrderStatusEnum;
use App\Models\User;
use App\Enums\UserRoleEnum;
use Illuminate\Http\Request;
use App\Http\Helpers\HelperFile;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\DoctorRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show_doctor')->only(['index', 'export', 'sale', 'show', 'export_sales']);
        $this->middleware('permission:create_doctor')->only(['create', 'store']);
        $this->middleware('permission:edit_doctor')->only(['edit', 'update']);
        $this->middleware('permission:delete_doctor')->only('destroy');
        $this->middleware('permission:block_doctor')->only(['block', 'unblock']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::onlyDoctor()->where('type', UserRoleEnum::Doctor);
        if (request('key_words')) {
            $data = $data->where(function ($q) {
                $key_words = request('key_words');
                $q->where('name', 'like', "%{$key_words}%")
                    ->orWhere('email', 'like', "%{$key_words}%");
            });
        }

        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.doctors.index', compact('data'));
    }

    // public function export(){
    //     $data = User::where('type' ,UserRoleEnum::Doctor)->select('name' , 'email' , 'is_block');
    //     if(request('key_words')){
    //         $data = $data->where(function ($q) {
    //             $key_words = request('key_words');
    //             $q->where('name', 'like', "%{$key_words}%")
    //                 ->orWhere('email', 'like', "%{$key_words}%");
    //         });
    //     }

    //     $data = $data->get()->makeHidden('is_block');
    //     return (new FastExcel($data))->download('doctors.xlsx');
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $data['type'] = UserRoleEnum::Doctor->value;

        if ($request->hasFile('image')) {
            $image = HelperFile::upload($request->file('image'), 'doctors');
            $data['image'] = $image['path'];
        }

        $doctors = User::create($data);

        $doctors->givePermissionTo([
            'show_product',
            'show_category',
            'show_package',
            'create_package',
            'edit_package',
            'delete_package',
            'show_invoice',
            'create_invoice',
            'edit_invoice'
        ]);

        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $doctor = User::onlyDoctor()->where('type', UserRoleEnum::Doctor->value)->findOrfail($id);
        $data = Invoice::with('invoice_items.product.commission_category')->whereIn('status', [OrderStatusEnum::Paid->value,  OrderStatusEnum::Send->value])->where('doctor_id', $id);


        $this_year = date('Y');
        $years = [];
        $months = [];
        for ($i = 0; $i < 5; $i++)
            $years[] = $this_year--;
        for ($i = 1; $i <= 12; $i++)
            $months[] = $i;
        $this_month = date('m');

        $search_year = date('Y');
        if (request('year')) {
            $search_year = request('year');
        }
        $search_month = date('m');
        if (request('month')) {
            $search_month = request('month');
        }
        $data = $data->whereYear('created_at', $search_year)->whereMonth('created_at', $search_month);


        $data = $data->paginate(config('app.paginate_number'));

        // return $data;
        return view('admin.doctors.show', compact('data', 'doctor', 'years', 'months', 'search_year', 'search_month'));
    }


    public function export(string $id)
    {
        //
        $doctor = User::onlyDoctor()->where('type', UserRoleEnum::Doctor->value)->findOrfail($id);
        $data = Invoice::with('invoice_items.product.commission_category')->whereIn('status', [OrderStatusEnum::Paid->value,  OrderStatusEnum::Send->value])->where('doctor_id', $id);


        $this_year = date('Y');
        $years = [];
        $months = [];
        for ($i = 0; $i < 5; $i++)
            $years[] = $this_year--;
        for ($i = 1; $i <= 12; $i++)
            $months[] = $i;
        $this_month = date('m');

        $search_year = date('Y');
        if (request('year')) {
            $search_year = request('year');
        }
        $search_month = date('m');
        if (request('month')) {
            $search_month = request('month');
        }
        $data = $data->whereYear('created_at', $search_year)->whereMonth('created_at', $search_month);


        $data = $data->get();




        $xls_data = [];
        foreach ($data as $item) {
            $xls_data[] = [
                __('messages.Reference') => $item->invoice_num,
                __('messages.Date created') => Carbon::parse($item->created_at)->format('Y-m-d'),
                __('messages.Total') => $item->total,
                __('messages.Doctors commission ( % )') => $item->doctor_commission,
                __('messages_301.Commission') => $item->doctor_commission_value,
            ];
        };

        $xls_data = collect($xls_data);

        $doctor_name = str_replace(" ", "-", $doctor->name);
        $name =   $doctor_name . "-doctor-sales";
        if (request('year')) {
            $name .= "-" . request('year');
        }

        if (request('month')) {
            $name .= "-" . request('month');
        }
        return (new FastExcel($xls_data))->download($name . '.xlsx');
    }


    public function sale($id)
    {
        $doctor = User::onlyDoctor()->where('type', UserRoleEnum::Doctor->value)->findOrfail($id);

        $data = InvoiceItem::with(['invoice', 'product'])->whereHas('invoice', function ($q) use ($id) {
            $q->whereIn('status', [OrderStatusEnum::Paid->value,  OrderStatusEnum::Send->value])->where("doctor_id", $id);
        })->paginate(config('app.paginate_number'));

        return view('admin.doctors.sale', compact('data', 'doctor'));
    }


    public function export_sale($id)
    {
        $doctor = User::onlyDoctor()->where('type', UserRoleEnum::Doctor->value)->findOrfail($id);

        $data = InvoiceItem::with(['invoice', 'product'])->whereHas('invoice', function ($q) use ($id) {
            $q->whereIn('status', [OrderStatusEnum::Paid->value,  OrderStatusEnum::Send->value])->where("doctor_id", $id);
        })->get();


        $xls_data = [];




        foreach ($data as $item) {
            $xls_data[] = [
                __('messages_301.Product name') => $item->product->name,
                __('messages_301.Code') => $item->product->code,
                __('messages_303.barcode') => $item->product->barcode,
                __('messages.Qty') => $item->qty,
                __('messages.Price') => $item->price,
                __('messages.Discount')  => $item->discount  . " % ",
                __('messages.Total before discount') => $item->total_befor_discount,
                __('messages.Total') => $item->total,
                __('messages.Date created') => $item->invoice->created_at_format,
            ];
        };

        $xls_data = collect($xls_data);

        $doctor_name = str_replace(" ", "-", $doctor->name);
        $name =   $doctor_name . "-doctor-sales";
        if (request('year')) {
            $name .= "-" . request('year');
        }

        if (request('month')) {
            $name .= "-" . request('month');
        }
        return (new FastExcel($xls_data))->download($name . '.xlsx');

        return view('admin.doctors.sale', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor->value)->findOrfail($id);
        return view('admin.doctors.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorRequest $request, string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor)->findOrfail($id);
        $data = $request->validated();

        $data = [
            "name" => $request->name,
            "clinic_name" => $request->clinic_name,
            "email" => $request->email,
            "mobile" => $request->mobile,
            "is_active" => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $data['type'] = UserRoleEnum::Doctor->value;
        if ($request->hasFile('image')) {
            $image = HelperFile::upload($request->file('image'), 'doctors');
            $data['image'] = $image['path'];
        }

        $item->update($data);

        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor)->findOrfail($id);
        $item->delete();
        HelperFile::delete($item->image);
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    public function block(string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor)->findOrfail($id);
        $item->is_block = 1;
        $item->save();
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    public function unblock(string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor)->findOrfail($id);
        $item->is_block = 0;
        $item->save();
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    public function approve(string $id)
    {
        $item = User::onlyDoctor()->where('type', UserRoleEnum::Doctor)->findOrfail($id);
        $item->is_active = 1;
        $item->save();
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }
}
