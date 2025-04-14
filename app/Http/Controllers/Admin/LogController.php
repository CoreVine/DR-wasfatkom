<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Models\CatchLog;
use RealRashid\SweetAlert\Facades\Alert;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware("permission:catch_logs")->only(["error_log" , "clear_error_log"]);
    }
    public function error_log(){
        $data = CatchLog::paginate(config("app.paginate_number"));
        return view("admin.logs.catch_log" , compact("data"));
    }

    public function clear_error_log(){

        CatchLog::query()->delete();
        Alert::toast(__("messages.done successfully") , "success");
        return back();
    }
}
