<?php

use App\Models\Product;
use App\Models\ProductTranslate;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mpdf\Mpdf;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get("test" ,  function(){
    // $html = view('admin.invoices.pdf' , compact('invoice'))->render();
    //  // إنشاء كائن mPDF
    // $mpdf = new Mpdf([
    //     'default_font' => 'amiri', // استخدام خط يدعم اللغة العربية
    // ]);

    // $mpdf->WriteHTML($html);
    // $mpdf->Output('my_pdf.pdf', 'I');

    // $products =  Test::orderBy("Items in english" , "asc")->where("Items in english" , "!=" , "Items in english")->get();
    // $index = 1;
    // $data = [];
    // $data_translate=[];


    // foreach($products as $product){
    //     $data[]=[
    //         "price"=>$product["price"] ,
    //         "image"=>null,
    //         "barcode"=>$product["barcode"] ,
    //         "code"=>$product["code"] ,
    //         "qty"=>$product->qty,
    //         "sale_qty"=>0,
    //         "remain_qty"=>$product["qty"],
    //         "is_active"=>1,
    //         "category_id"=>$product->brand_id ,
    //         "sub_category_id"=>null,
    //         "supplier_id"=>$product->vendor_id ?  $product->vendor_id : null,
    //         "expire_date"=>null ,
    //     ];

    //     $data_translate[]=[
    //         "lang"=>"ar",
    //         "name"=>$product["Items in arabic"],
    //         "description"=>$product["Items in arabic"],
    //         "product_id"=>$index,
    //     ];

    //     $data_translate[]=[
    //         "lang"=>"en",
    //         "name"=>$product["Items in english"],
    //         "description"=>$product["Items in english"],
    //         "product_id"=>$index,
    //     ];
    //     $index++;
    // }



    // Product::insert($data);
    // ProductTranslate::insert($data_translate);
    // return $data;

// });


// Route::get("translates", function () {



//     $dir_lang = '../lang/ar';
//    // $array = array_diff(scandir($dir_lang), array('.', '..'));

//    $array =["messages.php" , "messages_301.php" , "messages_303.php"];

//     foreach ($array as &$item) {
//         $file = $dir_lang . "/" . $item;
//         $file_base = str_replace("../", "", $file);
//         $filesave = str_replace("ar", "en", $file);

//         $translates = include(base_path($file_base));
//         $data = [];
//         foreach ($translates as $key => $value) {
//             if (is_string($value)) {
//                 $data[$key] = $key;
//             } else {
//                 $values_array = [];
//                 foreach ($value as $key_value => $val_value) {

//                     if ($key_value == "pending_end_time") {

//                         $values_array[$key_value] = "Publication period has expired";
//                     } else {

//                         $values_array[$key_value] = ucfirst(str_replace("_", " ", $key_value));
//                     }
//                 }
//                 $data[$key] = $values_array;
//             }
//         }
//         $headFile = "<?php \n return ";
//         $data = var_export($data, true) . ";";
//         $file_data = $headFile .= $data;
//         file_put_contents($filesave, $file_data);
//     }
// });
