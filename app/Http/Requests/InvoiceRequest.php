<?php

namespace App\Http\Requests;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /*
    public function authorize(): bool
    {
        return false;
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            "review_id" => "nullable|integer|exists:users,id",
            "doctor_id" => "required|integer|exists:users,id",
            "doctor_commission" => "required|numeric|max:50",
            "sub_total" => "required|numeric",
            "discount" => "required|numeric",
            "total" => "required|numeric",
            "client_name" => "required|string|max:255",
            "client_location" => "nullable|string|max:255",
            "client_mobile" => "required|string|max:255",
            "notes" => "nullable|string|max:10000",
            "items" => "required|array",
            "items.*.price" => "required|numeric",
            "items.*.qty" => "required|integer",
            "items.*.discount" => "nullable|numeric|min:0|max:100",
            "items.*.total" => "required|numeric",
            "items.*.total_befor_discount" => "required|numeric",
            "items.*.the_use" => "nullable|string|max:100000",
            "items.*.product_id" => "required|integer|exists:products,id,is_active,1",


            "packages" => "nullable|array",
            // "packages.*.price" => "required|numeric",
            // "packages.*.qty" => "required|integer",
            // "packages.*.total" => "required|numeric",
            // "packages.*.package_id" => "required|integer|exists:packages,id",


            // required_with:exemption_part.*.letter_number,exemption_part.*.letter_date_hijri|nullable|string


            "packages.*.price" => "required_with:packages.*.qty,packages.*.package_id|nullable|numeric",
            "packages.*.qty" => "required_with:packages.*.price,packages.*.package_id|nullable|integer",
            "packages.*.total" => "nullable|numeric",
            "packages.*.package_id" => "required_with:packages.*.price,packages.*.package_id|nullable|exists:packages,id",



        ];
    }
}
