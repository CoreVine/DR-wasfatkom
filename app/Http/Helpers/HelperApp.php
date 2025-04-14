<?php

namespace App\Http\Helpers;

use App\Enums\OrderStatusEnum;
use App\Models\Coupon;
use App\Models\CatchLog;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HelperApp
{
    public static function set_log_catch($fun_name, $message)
    {
        Log::info(["error_catch" => [
            "function_name" => $fun_name,
            "message" => $message,
            "date" => date('Y-m-d h:i')
        ]]);

        CatchLog::create([
            "function_name" => $fun_name,
            "message" => $message
        ]);
    }


    public static function check_coupon_use($coupon, $invoice_id)
    {
        $invoice = Invoice::where('id', $invoice_id)->first();

        if (!$invoice) {
            return [
                "status" => false,
                "message" => __("messages_301.This invoice is not found"),
            ];
        }
        $code = Coupon::where('code', $coupon)->whereHas('doctors', function ($q) use ($invoice) {
            $q->where('doctor_id', $invoice->doctor_id);
        })->active()->first();

        if (!$code) {
            return [
                "status" => false,
                "message" => __("messages_301.This is invalid coupon"),
            ];
        }
        $uses = Invoice::where('coupon_id', $code->id)->count();
        // dd($uses);
        // $user_use=TransactionCoupon::where('course_coupon_id',$code->id)->where('user_id',$user_id)->first();
        if ($code->to_date < today() || $code->from_date > today() || $uses >= $code->count_use || $invoice->coupon_id == $code->id) {
            return [
                "status" => false,
                "message" => __("messages_301.This coupon is invalid or expired"),
            ];
        }
        // if($user_use){
        //     return [
        //         "status"=>false,
        //         "message"=> __("messages_301.You used this coupon before"),
        //     ];
        // }

        return [
            "status" => true,
            "code" => $code,
            "invoice" => $invoice
        ];
    }










    public static function get_color_status($status)
    {
        if ($status == OrderStatusEnum::Draft->value) {
            return "warning";
        } elseif ($status == OrderStatusEnum::Review->value) {
            return "primary";
        } elseif ($status == OrderStatusEnum::Done->value) {
            return "primary";
        } elseif ($status == OrderStatusEnum::Paid->value) {
            return "secondary";
        } elseif ($status ==   OrderStatusEnum::Send->value) {
            return "success";
        } elseif ($status == OrderStatusEnum::Cancel->value) {
            return "danger";
        } elseif ($status == OrderStatusEnum::UnderDelivery->value) {
            return "dark";
        }
    }


    public static function calac_commission($invoice)
    {
        $total_products_not_commission = 0;

        foreach ($invoice->invoice_items as $item) {

            if (!$item->product->category->is_commission) {
                $total_products_not_commission += $item->total;
            }
        }





        $invoice_total = $invoice->total -  $total_products_not_commission;


        // calc tax
        $tax = 15 / 100;
        $total_tax =   $invoice_total   * $tax;


        $invoice_total =  $invoice_total -  $total_tax;





        $doctor_commission = $invoice->doctor_commission / 100;


        return  $invoice_total * $doctor_commission;
    }


    public static function make_qr_code($invoice_id)
    {
        include('../app/lib/phpqrcode/qrlib.php');

        $invoice = Invoice::where("id", $invoice_id)->first();





        $text = "شركة الندى الطبية" . "\n";
        $text .= "الرقم الضريبى : " . "310100358600003" . "\n";
        $text .= " التاريخ : " . Carbon::parse($invoice->create_at)->format("Y-m-d") . "\n";
        $text .= " المبلغ قبل الضريبة : " . $invoice->total - $invoice->tax_value . "\n";
        $text .= "  قيمة  الضريبة : " . $invoice->tax_value . "\n";
        $text .= " المبلغ شامل الضريبة : " . $invoice->total . "\n";


        $random_name = $invoice_id . ".png";

        // make dir
        $path = public_path() . '/uploads/invoice-qr';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);


        // make qr code
        \QRcode::png((string)$text, "uploads/invoice-qr/" . $random_name);

        return "invoice-qr/$random_name";
    }
}
