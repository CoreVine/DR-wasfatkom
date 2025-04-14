<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Illuminate\Support\ServiceProvider;

class CachSerivceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if(Schema::hasTable('settings')){
            if(!Cache::has('app_settings') || !Cache::has('app_settings_config')){

                Cache::put("app_settings" , Setting::pluck( 'value' , 'key'));
                Cache::put("app_settings_config" , Setting::whereNotNull('config_name')->pluck( 'value' , 'config_name'));


            }


            // set config

            //set config

            foreach(Cache::get("app_settings_config") as $config_name =>$val){
                config()->set($config_name , $val);
            }
        }


    }
}
