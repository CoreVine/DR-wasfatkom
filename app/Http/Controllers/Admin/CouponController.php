<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Models\User;
use App\Models\Coupon;
use App\Enums\UserRoleEnum;
use App\Models\AdminDoctor;
use App\Models\CouponDoctor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HelperApp;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\CouponRequest;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show_coupon')->only(['index', 'export']);
        $this->middleware('permission:create_coupon')->only(['create', 'store']);
        $this->middleware('permission:edit_coupon')->only(['edit', 'update']);
        $this->middleware('permission:active_coupon')->only(['active', 'in_active']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Coupon::where('user_id', auth()->id())->orderBy('id', 'desc');
        if (request('key_words')) {
            $data = $data->where(function ($q) {
                $key_words = request('key_words');
                $q->where('percentage', $key_words)
                    ->orWhere('count_use', $key_words);
            });
        }

        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.coupons.index', compact('data'));
    }

    public function export()
    {
        $data = Coupon::where('user_id', auth()->id())->orderBy('id', 'desc');
        if (request('key_words')) {
            $data = $data->where(function ($q) {
                $key_words = request('key_words');
                $q->where('percentage', $key_words)
                    ->orWhere('count_use', $key_words);
            });
        }

        $data = $data->get()->makeHidden(['created_at', 'updated_at', 'is_active']);
        return (new FastExcel($data))->download('coupons.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->is_active ? true : false;
        $coupon = Coupon::create($data);
        //
        if ($request->doctors) {
            $doctor_data = $this->doctor_data($request, $coupon->id);
            CouponDoctor::insert($doctor_data);
        }

        Alert::toast(__("messages.done successfully"), 'success');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $item = Coupon::where('user_id', auth()->id())->findOrFail($id);
        $doctors_id = CouponDoctor::where('coupon_id', $id)->pluck('doctor_id')->toArray();
        $all_doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctors_id)->pluck("email")->toArray();
        $all_doctors = implode(", ", $all_doctors);

        return view('admin.coupons.edit',  compact('item',  'all_doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, string $id)
    {
        //
        $coupon = Coupon::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->is_active ? true : false;

        $coupon->update($data);

        if ($request->doctors) {
            CouponDoctor::where('coupon_id', $id)->delete();
            $doctor_data = $this->doctor_data($request, $coupon->id);
            CouponDoctor::insert($doctor_data);
        }

        Alert::toast(__("messages.done successfully"), 'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function active($id)
    {
        $coupon = Coupon::where('user_id', auth()->id())->findOrFail($id);
        //

        $coupon->is_active = 1;
        $coupon->save();
        Alert::toast(__('messages.done successfully'), 'success');
        return back();
    }

    public function in_active($id)
    {
        $coupon = Coupon::where('user_id', auth()->id())->findOrFail($id);

        $coupon->is_active = 0;
        $coupon->save();
        Alert::toast(__('messages.done successfully'), 'success');

        return back();
    }


    public function get_doctors()
    {
        $user_id = auth()->id();
        $user = User::where('type', UserRoleEnum::Admin->value)->findOrFail($user_id);
        if ($user->hasPermissionTo('admins') || $user->is_all_doctor == "1") {
            $data = User::where('type', UserRoleEnum::Doctor->value)->select("id as value", "email", "name")->get();
            return ResponseHelper::sendResponseSuccess($data);
        } else {
            $doctors_id = AdminDoctor::where('admin_id', $user_id)->pluck('user_id')->toArray();
            $data = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctors_id)->select("id as value", "email", "name")->get();

            return ResponseHelper::sendResponseSuccess($data);
        }
    }

    public function doctor_data($request, $coupon)
    {
        $doctors = json_decode($request->doctors, true);
        $values = collect($doctors)->pluck('value')->all();
        $data = [];
        foreach ($values as $item) {
            $data[] = [
                'coupon_id' => $coupon,
                'doctor_id' => $item,
            ];
        }
        $all_doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $values)->get();
        if (count($all_doctors) == count($values))
            return $data;
        Alert::toast(__("messages.An error occurred in data entry", "error"));
        return back();
    }


   
}
