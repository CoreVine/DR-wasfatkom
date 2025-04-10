<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Models\Product;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class FormulationRequest extends FormRequest
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
        $languages = Language::active()->get();
        $rules = [];
        $rules = [
            "category_id" => ['required', 'integer', 'exists:categories,id,is_active,1'],
            "sub_category_id" => ['nullable', 'integer', 'exists:sub_categories,id,is_active,1'],
            "image" => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            "barcode" => 'nullable|string|max:255',
            'price' => 'required|min:0|numeric|decimal:2|regex:/^\d+(\.\d{1,2})?$/',
            'code' => 'required|string|max:255',
        ];
        foreach ($languages as $lang) {
            if ($lang->is_default) {
                $rules["name_$lang->code"] = "required|string|max:255";
                $rules["description_$lang->code"] = "required|string|max:5000";
            } else {
                $rules["name_$lang->code"] = "nullable|string|max:255";
                $rules["description_$lang->code"] = "nullable|string|max:5000";
            }
        }

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $product = Product::findOrFail($this->product);
            $rules['qty'] = 'required|integer|min:' . $product->sale_qty + $product->remain_qty;
        }

        return $rules;
    }
}
