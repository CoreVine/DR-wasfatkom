<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Models\Product;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

  public function rules(): array
  {
    $languages = Language::active()->get();
    $rules = [];
    $rules = [
      "category_id" => ['required', 'integer', 'exists:categories,id,is_active,1'],
      "supplier_id" => ['nullable', 'integer', 'exists:suppliers,id'],
      "sub_category_id" => ['nullable', 'integer', 'exists:sub_categories,id,is_active,1'],
      "image" => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
      "barcode" => 'nullable|string|max:255',
      'price' => 'required|min:0|numeric',
      'price_before_tax' => 'nullable|min:0|numeric',
      'qty' => 'required|integer|min:1',
      'tax' => 'required|min:0|max:100|numeric',
      'code' => 'required|string|max:255',
    ];
    foreach ($languages as $lang) {
      $rules["name_$lang->code"] = "required|string|max:255";
      $rules["description_$lang->code"] = "nullable|string|max:5000";
    }

    if (in_array($this->method(), ['PUT', 'PATCH'])) {
      $product = Product::findOrFail($this->product);
      $rules['qty'] = 'required|integer|min:' . $product->sale_qty + $product->remain_qty;
    }

    return $rules;
  }
}
