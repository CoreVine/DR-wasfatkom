<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    public function index($page){

         $settings = Setting::where('page' , $page)->get()->groupBy(function($item){
            return $item->group;
        });
        return view("admin.settings.page" , compact("settings" , "page"));
    }


    public function update(Request $request , $group){

        $groups_pages = Setting::pluck("group")->toArray();
        if(!in_array($group , $groups_pages)){
            abort(404);
        }

        $validation = Setting::where('group' , $group)->pluck("validation" , "key")->toArray();
        $data = $request->validate($validation);
        foreach($data  as $key=>$val){
            Setting::where("key" , $key)->update(["value"=>$val]);
        }


        Cache::forget("app_settings");
        Cache::forget("app_settings_config");


        Alert::toast(__("messages.done successfully") , "success");
        return back();

    }



}
