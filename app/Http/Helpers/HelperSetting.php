<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;

class HelperSetting
{

  public static function get_value($key)
  {
    if (Cache::has('app_settings')) {

      if (isset(Cache::get('app_settings')[$key])) {
        return Cache::get('app_settings')[$key];
      }
    }

    return null;
  }


}
