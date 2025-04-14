<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Throwable;
use App\Models\Category;
use App\Models\Language;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Helpers\HelperTranslate;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\SubCategoryRequest;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show_sub_category')->only(['index']);
        $this->middleware('permission:create_sub_category')->only(['create', 'store']);
        $this->middleware('permission:edit_sub_category')->only(['edit', 'update']);
        $this->middleware('permission:delete_sub_category')->only(['destroy']);
        $this->middleware('permission:active_sub_category')->only(['active', 'inactive']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = SubCategory::with('category')->orderBy('id', 'desc');
        if (request('key_words')) {
            $key_words = '%' . request('key_words') . '%';
            $data->where(function ($query) use ($key_words) {
                $query->whereHas('all_translate', function ($subQuery) use ($key_words) {
                    $subQuery->where('name', 'like', $key_words);
                });
            });
        }
        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.sub_categories.index', compact('data'));
    }

    public function export()
    {
        $data = SubCategory::orderBy('id', 'desc');

        if (request('key_words')) {
            $key_words = request('key_words');
            $data = $data->where('name', 'like', "%{$key_words}%");
        }

        $data = $data->get();
        return (new FastExcel($data))->download('Sub_Category.xlsx');
    }


    public function active_inactive($id)
    {
        $item = SubCategory::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $languages = Language::active()->orderDefaultActive()->get();
        $categories = Category::active()->select('id')->get();
        return view('admin.sub_categories.create', compact('languages', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryRequest $request)
    {
        //
        DB::beginTransaction();
        try {
            $data = $request->validated();
            // create data
            if ($request->hasFile('image')) {
                $file = HelperFile::upload($request->file('image'), 'sub_categories');
                $data['image'] = $file['path'];
            }

            $item = SubCategory::create($data);


            HelperTranslate::set_translate($request, SubCategory::class, $item->id);

            DB::commit();
            // return back with messsage
            Alert::toast(__("messages.done successfully"), "success");
            return back();
        } catch (Throwable $e) {

            // delete data
            DB::rollBack();
            // set log

            HelperApp::set_log_catch("Store  sub_category", $e->getMessage());

            // return back with messsage
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
        //
        $item = SubCategory::findOrFail($id);
        $languages = Language::active()->orderDefaultActive()->get();
        $categories = Category::active()->select('id')->get();
        return view('admin.sub_categories.edit', compact('languages', 'categories', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubCategoryRequest $request, string $id)
    {
        //
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $item = SubCategory::findOrFail($id);

            $old_image = $item->image;
            // create data
            if ($request->hasFile('image')) {
                HelperFile::delete($old_image);
                $file = HelperFile::upload($request->file('image'), 'sub_categories');
                $data['image'] = $file['path'];
            }

            $item->update($data);


            HelperTranslate::set_translate($request, SubCategory::class, $item->id);

            DB::commit();
            // return back with messsage
            Alert::toast(__("messages.done successfully"), "success");
            return back();
        } catch (Throwable $e) {

            // delete data
            DB::rollBack();
            // set log

            HelperApp::set_log_catch("Update  sub_category", $e->getMessage());

            // return back with messsage
            Alert::toast(__("messages.An error occurred in data entry"), "error");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = SubCategory::where('id', $id)->first();
        $item->delete();
        HelperFile::delete($item->image);
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }
}
