<?php

namespace Database\Seeders;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $settings =[


            [
                "name"=>"Doctors commission ( % )",
                "key"=>"general_account_doctors_commission",
                "value"=>"10",
                "group"=>"Account",
                "page"=>"General",
                "type"=>"number",
                "validation"=>"required|numeric|max:50",
                "config_name"=>null,
                "list_values"=>null
            ],

            [
                "name"=>"Tax ( % )",
                "key"=>"general_tax",
                "value"=>"10",
                "group"=>"Tax",
                "page"=>"General",
                "type"=>"number",
                "validation"=>"required|numeric|max:50",
                "config_name"=>null,
                "list_values"=>null
            ],


        //    [
        //     "name"=>"Payment via",
        //     "key"=>"payment_via",
        //     "value"=>"myfatoorah",
        //     "group"=>"Payment via",
        //     "page"=>"Payment",
        //     "type"=>"radio",
        //     "validation"=>"required|in:myfatoorah",
        //     "config_name"=>null,
        //     "list_values"=>"myfatoorah"
        //    ],

           [
            "name"=>"Test Or Live",
            "key"=>"payment_myfatoorah_test_or_live",
            "value"=>"test",
            "group"=>"My Fatoorah",
            "page"=>"Payment",
            "type"=>"radio",
            "validation"=>"required|in:test,live",
            "config_name"=>null,
            "list_values"=>"test,live"
           ],


           [
            "name"=>"Region",
            "key"=>"payment_myfatoorah_country_iso",
            "value"=>"KWT",
            "group"=>"My Fatoorah",
            "page"=>"Payment",
            "type"=>"text",
            "validation"=>"required|string",
            "config_name"=>null,
            "list_values"=>null
           ],

           [
            "name"=>"Currency",
            "key"=>"payment_myfatoorah_currency",
            "value"=>"KWD",
            "group"=>"My Fatoorah",
            "page"=>"Payment",
            "type"=>"text",
            "validation"=>"required|string",
            "config_name"=>null,
            "list_values"=>null
           ],

           [
            "name"=>"Api Key",
            "key"=>"payment_myfatoorah_api_key",
            "value"=>"rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL",
            "group"=>"My Fatoorah",
            "page"=>"Payment",
            "type"=>"textarea",
            "validation"=>"required|string",
            "config_name"=>null,
            "list_values"=>null
           ],


           [
            "name"=>"Email",
            "key"=>"communication_email",
            "value"=>"pengroup@gmail.com",
            "group"=>"Contacts",
            "page"=>"Communication",
            "type"=>"email",
            "validation"=>"required|email|max:255",
            "config_name"=>null,
            "list_values"=>null
           ],

           [
            "name"=>"Address",
            "key"=>"communication_address",
            "value"=>"مصر ,القاهرة",
            "group"=>"Contacts",
            "page"=>"Communication",
            "type"=>"text",
            "validation"=>"required|string|max:255",
            "config_name"=>null,
            "list_values"=>null
           ],
           [
            "name"=>"Mobile",
            "key"=>"communication_mobile",
            "value"=>"010",
            "group"=>"Contacts",
            "page"=>"Communication",
            "type"=>"text",
            "validation"=>"required|string|max:255",
            "config_name"=>null,
            "list_values"=>null
           ],


        ];




        Cache::forget("app_settings");
        Cache::forget("app_settings_config");
        Setting::insert($settings);
    }
}
