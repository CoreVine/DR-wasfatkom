<?php

namespace App\Payments;

use App\Http\Helpers\HelperSetting;
use Illuminate\Support\Facades\Http;

class TamaraTransaction
{
    private static $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI0NzQ0NmUxMC0yOTJiLTQzY2UtYTJmOS0zNWY0NTc5YjZiZTQiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiYzhlMmYwZGE4NmVhMTk3MjQ1ZmRkNjgxODA3NGFhZGQiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTY5NTYyMjg5MCwiaXNzIjoiVGFtYXJhIFBQIn0.OZcoDYXOi7wITf-_uOf6FZrW-hIGkWJZZi837uTWB2q0vIORzz4AiRkj5Xw6dSRf61v6x6QsRo99kTbVArJqnCNMmkzpicEHJwFupZrDikST9YJaZsRCEBCZkTWPa8f17GPLMEGTSJhhUTjfs0nJ5e_Ki3FhvHGFombRZzBa9sjBL2olsroJK3qZQhQ9VuM6bPXB0BAgJZJHE4RHwzb1ina0tQ9E7HzePbtCRuyT_NQHasUg2Q5gEsk1qDDvpS6bE2pXhZXPxLnsV9R-kQ_cEJgmOM8vi19hblsYDfzE4IPatld8ESXnwd8b9HdI6TovYvto3s___sdGEnF--nS8qQ";
    public static function get_types($order)
    {
        $response = Http::withToken(self::$token)->get(
            "https://api.tamara.co/checkout/payment-types",
            [
                'country' => "SA",
                "currency" => "SAR",
                "order_value" => $order->total,
                "phone" => $order->client_mobile
            ]
        );


        return $response->json();
    }

    public static function get_types_payments($order)
    {
        $response = Http::withToken(self::$token)
            ->post(
                "https://api.tamara.co/checkout/payment-options-pre-check",
                [
                    "country" => "SA",
                    "order_value" => [
                        "amount" => $order->total,
                        "currency" => "SAR",
                    ],
                    "phone" => $order->client_mobile,

                ]
            );
        return $response->json();
    }


    public static function create($order)
    {
        $payload = self::payload($order);
        $res = Http::withToken(self::$token)->post("https://api.tamara.co/checkout" , $payload);
        return $res->json();
    }

    public static function payload($order)
    {

        return [
            "order_reference_id" => $order->payment_order_id,
            "order_number" => $order->invoice_num,
            "total_amount" => [
                "amount" => $order->total,
                "currency" => "SAR"
            ],
            "description" => "string",
            "country_code" => "SA",
            "payment_type" => "PAY_BY_INSTALMENTS",
            "instalments" => null,
            "locale" => "en_US",
            "items" => [
                [
                    "reference_id" => $order->payment_order_id,
                    "type" => "physical",
                    "name" =>  $order->invoice_num,
                    "sku" =>  $order->invoice_num,
                    "quantity" => 1,
                    "total_amount" => [
                        "amount" => $order->total,
                        "currency" => "SAR"
                    ]
                ]
            ],
            "consumer" => [
                "first_name" =>$order->client_name,
                "last_name" => $order->client_name,
                "phone_number" => $order->client_mobile,
                "email" => HelperSetting::get_value('communication_email')
            ],
            "shipping_address" => [
                "first_name" => $order->client_name,
                "last_name" => $order->client_name,
                "line1" => "3764 Al Urubah Rd",
                "city" =>  $order->client_location ?  $order->client_location :   "Riyadh",
                "country_code" => "SA",
                "phone_number" =>  $order->client_mobile
            ],
            "tax_amount" => [
                "amount" => "0",
                "currency" => "SAR"
            ],
            "shipping_amount" => [
                "amount" => "0",
                "currency" => "SAR"
            ],
            "merchant_url" => [
                "success" => route('payment.tamara.callback'),
                "failure" => route('payment.tamara.callback'),
                "cancel" => route('payment.tamara.callback'),
                "notification" => route('payment.tamara.callback')
            ],
        ];
    }
}
