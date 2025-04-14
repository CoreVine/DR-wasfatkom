<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\AdminDoctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Rap2hpoutre\FastExcel\FastExcel;

class AdminController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:admins');
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = User::where('id', '!=', auth()->id())->where('type', UserRoleEnum::Admin);
    if (request('key_words')) {
      $data = $data->where(function ($q) {
        $key_words = request('key_words');
        $q->where('name', 'like', "%{$key_words}%")
          ->orWhere('email', 'like', "%{$key_words}%");
      });
    }

    $data = $data->paginate(config('app.paginate_number'));
    return view('admin.admins.index', compact('data'));
  }


  public function export()
  {
    $data = User::where('id', '!=', auth()->id())->select('name', 'email', 'is_block')->where('type', UserRoleEnum::Admin);
    if (request('key_words')) {
      $data = $data->where(function ($q) {
        $key_words = request('key_words');
        $q->where('name', 'like', "%{$key_words}%")
          ->orWhere('email', 'like', "%{$key_words}%");
      });
    }

    $data = $data->get()->makeHidden('is_block');
    return (new FastExcel($data))->download('admins.xlsx');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {


    return view('admin.admins.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AdminRequest $request)
  {


    $data = [
      "name" => $request->name,
      "email" => $request->email,
      "password" => bcrypt($request->password),
      "type" => UserRoleEnum::Admin,
      'is_all_doctor' => $request->is_all_doctor ? true : false,
    ];


    $admin = User::create($data);
    if ($request->permissions) {
      $admin->givePermissionTo($request->permissions);
    }
    if (!$request->is_all_doctor) {
      $doctor_data = $this->doctor_data($request, $admin->id);
      AdminDoctor::insert($doctor_data);
    }

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {

    // return auth('web')->id();
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $item = User::where("type", UserRoleEnum::Admin)->where('id', '!=', auth()->id())->findOrfail($id);
    $permissions_admin = $item->getAllPermissions()->pluck('name')->toArray();
    $doctors_id = AdminDoctor::where('admin_id', $id)->pluck('user_id')->toArray();
    $all_doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctors_id)->pluck("email")->toArray();
    $all_doctors = implode(", ", $all_doctors);

    return view('admin.admins.edit', compact('item', 'permissions_admin', 'all_doctors'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(AdminRequest $request, string $id)
  {
    //
    $admin = User::where("type", UserRoleEnum::Admin)->where('id', '!=', auth()->id())->findOrfail($id);
    $data = [
      "name" => $request->name,
      "email" => $request->email,
      "type" => UserRoleEnum::Admin,
      'is_all_doctor' => $request->is_all_doctor ? true : false,
    ];

    if ($request->password) {
      $data["password"] = bcrypt($request->password);
    }


    $admin->update($data);
    if ($request->permissions) {
      $admin->syncPermissions($request->permissions);
    }

    if ($request->is_all_doctor) {
      AdminDoctor::where('admin_id', $admin->id)->delete();
    } else {
      AdminDoctor::where('admin_id', $admin->id)->delete();
      $doctor_data = $this->doctor_data($request, $admin->id);
      AdminDoctor::insert($doctor_data);
    }

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $item = User::where('id', '!=', auth()->id())->where('type', UserRoleEnum::Admin)->findOrfail($id);
    $item->delete();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }


  public function block(string $id)
  {
    $item = User::where('id', '!=', auth()->id())->where('type', UserRoleEnum::Admin)->findOrfail($id);
    $item->is_block = 1;
    $item->save();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function unblock(string $id)
  {
    $item = User::where('id', '!=', auth()->id())->where('type', UserRoleEnum::Admin)->findOrfail($id);
    $item->is_block = 0;
    $item->save();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function get_doctors()
  {
    $data = User::where('type', UserRoleEnum::Doctor->value)->select("id as value", "email", "name")->get();
    return ResponseHelper::sendResponseSuccess($data);
  }

  public function doctor_data($request, $admin)
  {
    $doctors = json_decode($request->doctors, true);
    $values = collect($doctors)->pluck('value')->all();
    $data = [];
    foreach ($values as $item) {
      $data[] = [
        'admin_id' => $admin,
        'user_id' => $item,
      ];
    }
    $all_doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $values)->get();
    if (count($all_doctors) == count($values))
      return $data;
    Alert::toast(__("messages.An error occurred in data entry", "error"));
    return back();
  }

  public function change_password(Request $request) {
    $request->validate([
      'current_password' => 'required',
      'new_password' => 'required|confirmed|min:8|max:255'
    ]);

    $user = Auth::user();

    if (!Hash::check($request->input('current_password'), $user->getAuthPassword())) {
      Alert::toast(__("messages.Invalid Current Password"));
      return back();
    }

    User::where('id', $user->getAuthIdentifier())->update([
      'password' => Hash::make($request->input('new_password'))
    ]);

    Alert::toast(__("messages.Password Changed Successfully"));
    return back();
  }
}
