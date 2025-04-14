<?php

namespace App\Http\Helpers;


use App\Models\Language;
use Illuminate\Support\Facades\Schema;

class HelperTranslate
{



  public static function set_translate($request, $model, $model_id)
  {

    $model_name = explode("\\", $model);

    $model_name = end($model_name);
    $model_translate_name = $model . "Translate";

    $model = self::from_camel_case($model_name);
    // $model_name_lower =  $model ;



    $model_translate_name::where($model . "_id", $model_id)->delete();

    $columns = Schema::getColumnListing($model . "_translates");


    $languages = Language::active()->get();


    $data_translate_all = [];
    foreach ($languages as $lang) {
      $data = [];
      $is_all_data_other_lang = true;
      foreach ($columns as $col) {

        if (!in_array($col, ["id", "created_at", "updated_at"])) {

          if ($col == $model . "_id" || $col == "lang") {

            if ($col == "lang") {
              $data["lang"] = $lang->code;
            } else {
              $data[$model . "_id"] = $model_id;
            }
          } else {
            if ($lang->is_default) {
              $data[$col] = $request[$col . "_" . $lang->code];
            } else {

              if (isset($request[$col . "_" . $lang->code]) && $request[$col . "_" . $lang->code]) {
                $data[$col] = $request[$col . "_" . $lang->code];
              } else {
                $is_all_data_other_lang = false;
              }
            }
          }
        }
      }

      if (!empty($data) && $is_all_data_other_lang) {
        $data_translate_all[] = $data;
      }
    }

    $model_translate_name::insert($data_translate_all);
  }



  public static function get_value($item, $lang, $col)
  {
    if (isset($item->all_translate->groupBy('lang')[$lang])) {
      return $item->all_translate->groupBy('lang')[$lang][0][$col];
    }

    return null;
  }


  public static function from_camel_case($input)
  {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
      $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
  }
}
