<?php

namespace App\Payments;

use App\Enums\OrderStatusEnum;
use App\Http\Helpers\HelperSetting;
use App\Models\Invoice;
use App\Models\Order;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;


class MyFatooraTransaction{



    public static function create($order){


        $curlData = self::getPayLoadData($order);

        $mfObj   = new MyFatoorahPayment(self::get_config());

        $payment = $mfObj->getInvoiceURL($curlData, 0, $order,null);

        return $payment['invoiceURL'];

    }



    public static function check_payment($transaction_id){


        $mfObj = new MyFatoorahPaymentStatus( self::get_config());
         $data  = $mfObj->getPaymentStatus($transaction_id , 'PaymentId');



        if($data->InvoiceStatus != "PAID" && $data->InvoiceStatus != "Paid"){
            if($data->InvoiceStatus == "Failed"){
                return [
                    "status"=>false,
                    "message"=>$data->InvoiceError,
                    "customer_reference"=>$data->CustomerReference
                ];
            }else{
                return [
                    "status"=>false,
                    "message"=>"Payment time has expired",
                    "customer_reference"=>$data->CustomerReference
                ];
            }
        }else{

            $order = Invoice::where("payment_order_id" , $data->CustomerReference)->first();

            if(!$order){
                return [
                    "status"=>false,
                    "message"=>"Notfound Checkout order",
                    "customer_reference"=>$data->CustomerReference
                ];
            }


            $order_amount = $order->total ;


            if($order_amount !=  $data->InvoiceValue ){
                return [
                    "status"=>false,
                    "message"=>"The amount paid is incorrect",
                    "customer_reference"=>$data->CustomerReference
                ];
            }


            $order->update([
                "status"=>OrderStatusEnum::Paid->value
            ]);

            return [
                "customer_reference"=>$data->CustomerReference,
                "status"=>true,
                "message"=>"The payment was completed successfully",
                "transaction_data"=>[
                    "order_id"=>$order->order_id,
                    "payment_via"=>"myfatoora",
                    "payment_data"=>$data,
                    "amount"=> $order->total,
                ]

            ];


        }

    }



    private static function getPayLoadData($order) {
        $callbackURL = route('payment.myfatoora.callback');
        $ErrorUrl = route('payment.myfatoora.error');

        //You can get the data using the order object in your system


        return [
            'CustomerName'       =>$order->client_name,
            'InvoiceValue'       => $order->total,
            'DisplayCurrencyIso' =>  "SAR",
            'CustomerEmail'      => null,
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $ErrorUrl,
            'MobileCountryCode'  => null,
            'CustomerMobile'     =>  $order->client_mobile,
            'Language'           => 'en',
            'CustomerReference'  => $order->payment_order_id,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION
        ];
    }



    private  static function get_config() {
        return [
            'apiKey'      => HelperSetting::get_value('payment_myfatoorah_api_key'),
            'isTest'      =>   false,
            'countryCode' =>  "SAU",
        ];

    }




}
