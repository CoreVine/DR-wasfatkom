<?php

namespace Database\Seeders;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = json_decode(file_get_contents(asset('lang.json')));

        $data= [];
        foreach($languages as $lang){

            $is_active = 0;
            $is_default = 0;
            if(in_array($lang->code , ['ar' , 'en'])){
                $is_active =1;
                if($lang->code == "ar"){
                    $is_default = 1;
                }
            }
            $data[]=[
                "code"=>$lang->code,
                "name"=>$lang->name,
                "native_name"=>$lang->nativeName,
                "is_active"=>$is_active,
                "is_default"=>$is_default,
            ];
        }

        Language::insert($data);
    }
}
