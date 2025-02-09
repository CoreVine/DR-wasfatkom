<?php

namespace App\Http\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HelperFile
{
    public static function uploadMulti($files, $type)
    {
        $names = [];
        foreach ($files as $file) {
            $extenstion = $file->getClientOriginalExtension();
            $fileName = self::randText() . "." . $extenstion;
            $file->move(self::folderSave() . $type, $fileName);
            $names[] = ['name' => $fileName];
        }
        return $names;
    }
/*       $file = $request->file('file');

        // Specify the type (e.g., 'images' or 'avatars')
        $type = 'images'; // Example type

        // Upload the file using the helper function
        $uploadResult = FileHelper::upload($file, $type);
*/


    public static function upload($file, $type)
    {

        $extenstion = $file->getClientOriginalExtension();
        $fileName = self::randText() . "." . $extenstion;
        $file->move(self::folderSave() . $type, $fileName);
        $path_file = "uploads/" . trim($type, "/") . "/" . $fileName;
        return  ['path' => $path_file];
    }


    public static function deleteMultiFiles($files, $type = null)
    {
        foreach ($files as $file) {

            if ($file) {
                if ($type) {
                    if (File::exists($type . "/" . $file)) {
                        unlink($type . "/" . $file);
                    }
                } else {
                    if (File::exists($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }

    public static function delete($fileName, $type = null)
    {

        if(!in_array($fileName , ["default-images/category.png"])){
            if ($type) {
                if (File::exists($type . "/" . $fileName)) {
                    unlink($type . "/" . $fileName);
                }
            } else {

                if (File::exists($fileName)) {
                    unlink($fileName);
                }
            }
        }

    }



    private static function randText()
    {
        return Str::random(20) . time();
    }


    private static function folderSave()
    {
        return  public_path('uploads/');
    }

    public static function files_type(){
        return [
            "jpg"=>"image",
            "png"=>"image",
            "jpeg"=>"image",
            "mp4"=>"video",
        ];
    }


    public static function generate_barcode($product_id){
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $image = $generator->getBarcode($product_id, $generator::TYPE_CODE_128);
        Storage::disk('uploads')->put("barcodes/$product_id.png", $image);
    }






}
