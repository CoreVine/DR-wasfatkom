<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Throwable;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Helpers\HelperTranslate;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show_category')->only(['index', 'export']);
        $this->middleware('permission:create_category')->only(['create', 'store']);
        $this->middleware('permission:edit_category')->only(['edit', 'update']);
        $this->middleware('permission:delete_category')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Category::orderBy('id', 'desc');

        if (request('key_words')) {
            $key_words = '%' . request('key_words') . '%';
            $data->where(function ($query) use ($key_words) {
                $query->whereHas('all_translate', function ($subQuery) use ($key_words) {
                    $subQuery->where('name', 'like', $key_words);
                });
            });
        }
        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.categories.index', compact('data'));
    }

    // public function export()
    // {
    //     $data = Category::orderBy('id', 'desc');
    //     if (request('key_words')) {
    //         $key_words = '%' . request('key_words') . '%';
    //         $data->where(function ($query) use ($key_words) {
    //             $query->whereHas('all_translate', function ($subQuery) use ($key_words) {
    //                 $subQuery->where('name', 'like', $key_words)
    //                     ->orWhere('description', 'like', $key_words);
    //             });
    //         });
    //     }

    //     $data = $data->get();
    //     return (new FastExcel($data))->download('Example.xlsx');
    // }

    public function active_inactive($id)
    {
        $item = Category::findOrFail($id);
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
        $languages = Language::active()->orderDefaultActive()->get();
        return view('admin.categories.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();
            if ($request->hasFile('image')) {
                $file = HelperFile::upload($request->file('image'), 'categories');
                $data['image'] = $file['path'];
            }

            $data['is_commission'] = $request->is_commission ? true : false;


            $categories = Category::create($data);
            HelperTranslate::set_translate($request, Category::class, $categories->id);
            DB::commit();

            Alert::toast(__("messages.done successfully"), "success");
            return back();
        } catch (Throwable $e) {

            DB::rollBack();

            HelperApp::set_log_catch("Store categories", $e->getMessage());

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
        $item = Category::findOrFail($id);
        $languages = Language::active()->orderDefaultActive()->get();
        return view('admin.categories.edit', compact('languages', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        DB::beginTransaction();

        try {

            $item = Category::findOrFail($id);
            $data = $request->validated();
            $old_image = $item->image;
            if ($request->hasFile('image')) {
                
                HelperFile::delete($old_image);
                $file = HelperFile::upload($request->file('image'), 'categories');
                $data['image'] = $file['path'];
            }
            $data['is_commission'] = $request->is_commission ? true : false;

            $item->update($data);


            HelperTranslate::set_translate($request, Category::class, $item->id);
            DB::commit();
            Alert::toast(__("messages.done successfully"), "success");
            return back();
        } catch (Throwable $e) {

            DB::rollBack();

            HelperApp::set_log_catch("update Category", $e->getMessage());

            Alert::toast(__("messages.An error occurred in data entry"), "error");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = Category::findOrfail($id);
        $categories->delete();
        HelperFile::delete($categories->image);

        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }
}
