<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{

  public function rules(): array
  {
    return [
      "review_id" => "nullable|integer|exists:users,id",
      "doctor_id" => "required|integer|exists:users,id",
      "doctor_commission" => "required|numeric|max:50",
      "sub_total" => "required|numeric",
      //"discount" => "required|numeric|min:0|max:100",
      "total" => "required|numeric",
      "client_name" => "required|string|max:255",
      "client_location" => "nullable|string|max:255",
      "client_mobile" => "required|string|max:255",
      "notes" => "nullable|string|max:10000",
      "items" => "required|array",
      "items.*.price" => "required|numeric",
      "items.*.qty" => "required|integer",
      "items.*.discount" => "nullable|numeric|min:0",
      "items.*.total" => "required|numeric",
      "items.*.total_befor_discount" => "required|numeric",
      "items.*.the_use" => "nullable|string|max:100000",
      "items.*.product_id" => "required|integer|exists:products,id,is_active,1",

      "packages" => "nullable|array",
      "formulations" => "nullable|array",

      "packages.*.price" => "nullable|numeric",
      "packages.*.qty" => "nullable|integer",
      "packages.*.total" => "nullable|numeric",
      "packages.*.package_id" => "required_with:packages.*.price,packages.*.package_id|nullable|exists:packages,id",

      "formulations.*.price" => "nullable|numeric",
      "formulations.*.qty" => "nullable|integer",
      "formulations.*.discount" => "nullable|numeric|min:0",
      "formulations.*.total" => "nullable|numeric",
      "formulations.*.formulation_id" => "required_with:formulations.*.price,formulations.*.formulation_id|nullable|exists:formulations,id",
    ];
  }
}
