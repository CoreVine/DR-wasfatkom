<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Throwable;
use App\Models\User;
use App\Models\Package;
use App\Models\Product;
use App\Models\Language;
use App\Enums\UserRoleEnum;
use App\Models\AdminDoctor;
use Illuminate\Http\Request;
use App\Models\PackageProduct;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Helpers\HelperTranslate;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\PackageTranslate;

class PackageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:show_package')->only(['index', 'export']);
        $this->middleware('permission:create_package')->only(['create', 'store']);
        $this->middleware('permission:edit_package')->only(['edit', 'update']);
        $this->middleware('permission:delete_package')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);


        if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
            $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctor_ids)->select('id', 'name')->get();
            $data = Package::whereIn('doctor_id', $doctor_ids)->orderBy('id', 'desc');
            if (!$doctors && $user->is_all_doctor == "0") {
                return abort(404);
            }
        } elseif ($user->type->value == UserRoleEnum::Doctor->value) {
            $data = Package::where('doctor_id', $user->id)->orderBy('id', 'desc');
        } else

            $data = Package::orderBy('id', 'desc');


        if (request('key_words')) {
            $key_words = '%' . request('key_words') . '%';
            $data->where(function ($query) use ($key_words) {
                $query->whereHas('all_translate', function ($subQuery) use ($key_words) {
                    $subQuery->where('name', 'like', $key_words);
                });
            });
        }
        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.packages.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $languages = Language::active()->orderDefaultActive()->get();
        $products = Product::active()->select('id')->get();
        if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
            $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->whereIn('id', $doctor_ids)->select('id', 'name')->get();
            if (!$doctors && $user->is_all_doctor == "0") {
                return abort(404);
            }
        } else
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->select('id', 'name')->get();

        return view('admin.packages.create', compact('languages', 'products', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PackageRequest $request)
    {

        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['price'] = Product::whereIn("id", $request->products)->sum("price");
            if ($request->hasFile('image')) {
                $file = HelperFile::upload($request->file('image'), 'packages');
                $data['image'] = $file['path'];
            }
            $packages = Package::create($data);

            // $this->savePackageProducts($request->input('products'), $packages->id);

            //HelperTranslate::set_translate($request, Package::class, $packages->id);

            PackageTranslate::create([
                'package_id' => $packages->id,
                'lang' => 'ar',
                'name' => $request->name_ar,
                'description' => $request->description_ar,
            ]);


            PackageTranslate::create([
                'package_id' => $packages->id,
                'lang' => 'en',
                'name' => $request->name_en,
                'description' => $request->description_en,
            ]);

            $packages->products()->attach($request->products);
            DB::commit();

            Alert::toast(__("messages.done successfully"), "success");

            return back();
        } catch (Throwable $e) {

            DB::rollBack();

            HelperApp::set_log_catch("Store package", $e->getMessage());

            Alert::toast(__("messages.An error occurred in data entry"), "error");
            return back();
        }
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

        $languages = Language::active()->orderDefaultActive()->get();
        $products = Product::active()->select('id')->get();
        $en = PackageTranslate::where('package_id', $id)->where('lang', 'en')->get()->first();
        $ar = PackageTranslate::where('package_id', $id)->where('lang', 'ar')->get()->first();

        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        if ($user->type->value == UserRoleEnum::Doctor->value) {
            $item = Package::where('doctor_id', $user->id)->findOrFail($id);
        } else

            $item = Package::with('all_translate')->findOrFail($id);


        if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
            $doctors = User::where('type', UserRoleEnum::Doctor->value);
            if (!$user->is_all_doctor) {
                $doctor_ids = AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
                $doctors  =  $doctors->whereIn('id', $doctor_ids);
            }

            $doctors = $doctors->select('id', 'name')->get();
            $item = Package::with('all_translate')->whereIn('doctor_id', $doctor_ids)->findOrFail($id);
            if (!$doctors && $user->is_all_doctor == "0") {
                return abort(404);
            }
        } else
            $doctors = User::where('type', UserRoleEnum::Doctor->value)->select('id', 'name')->get();

        return view('admin.packages.edit', compact('languages', 'products', 'item', 'doctors', 'en', 'ar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PackageRequest $request, string $id)
    {
        DB::beginTransaction();

        try {

            $data = $request->validated();
            $data['price'] = Product::whereIn("id", $request->products)->sum("price");
            $package = Package::findOrFail($id);

            $old_image = $package->image;
            // create data
            if ($request->hasFile('image')) {
                HelperFile::delete($old_image);
                $file = HelperFile::upload($request->file('image'), 'packages');
                $data['image'] = $file['path'];
            }

            $package->update($data);

            PackageTranslate::updateOrCreate([
                'package_id' => $package->id,
                'lang' => 'ar',
            ], [
                'name' => $request->name_ar,
                'package_id' => $package->id,
                'lang' => 'ar',
                'description' => $request->description_ar,
            ]);

            PackageTranslate::updateOrCreate([
                'package_id' => $package->id,
                'lang' => 'en',
            ], [
                'name' => $request->name_en,
                'package_id' => $package->id,
                'lang' => 'en',
                'description' => $request->description_en,
            ]);

            // HelperTranslate::set_translate($request, Package::class, $package->id);

            $package->products()->sync($request->products);


            DB::commit();

            Alert::toast(__("messages.done successfully"), "success");

            return back();
        } catch (Throwable $e) {
            DB::rollBack();

            HelperApp::set_log_catch("Update package", $e->getMessage());

            Alert::toast(__("messages.An error occurred in data update"), "error");

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $packages = Package::findOrFail($id);
        $packages->delete();
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }
}
