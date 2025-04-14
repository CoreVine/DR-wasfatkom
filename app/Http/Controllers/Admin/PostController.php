<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperTranslate;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Language;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;
class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create_post')->only(['create' , 'store']);
        $this->middleware('permission:edit_post')->only(['edit' , 'update']);
        $this->middleware('permission:delete_post')->only(['destroy']);
        $this->middleware('permission:active_post')->only(['active' , 'inactive']);
        $this->middleware('permission:force_delete_post')->only(['force_delete']);
        $this->middleware('permission:restore_post')->only(['restore']);

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post::paginate(config('app.paginate_number'));
        return view('admin.posts.index' , compact('data'));

    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $languages = Language::active()->orderDefaultActive()->get();

        return view('admin.posts.create' , compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {


        DB::beginTransaction();

        try{

            // create data
            $post = Post::create();
            HelperTranslate::set_translate($request , Post::class , $post->id);

            DB::commit();
            // return back with messsage
            Alert::toast(__("messages.done successfully") , "success");
            return back();

        }catch(Throwable $e){

            // delete data
            DB::rollBack();
            // set log

            HelperApp::set_log_catch("Store product" , $e->getMessage());

            // return back with messsage
            Alert::toast(__("messages.An error occurred in data entry") , "error");
            return back();
        }


    }


    public function active_inactive($id){
        $post = Post::findOrFail($id);
        $post->is_active = !$post->is_active;
        $post->save();

        Alert::toast(__("messages.done successfully") , "success");
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        Alert::toast(__("messages.done successfully") , "success");
        return back();
    }

    public function restore($id){
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        Alert::toast(__("messages.done successfully") , "success");
        return back();
    }

    public function force_delete($id){
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();
        Alert::toast(__("messages.done successfully") , "success");
        return back();
    }



}
