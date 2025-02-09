<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class TrashedController extends Controller
{
    public function posts(){
        $data =  Post::onlyTrashed()->paginate(config("app.paginate_number"));
        return view("admin.trashed.posts" , compact("data"));
    }
}
