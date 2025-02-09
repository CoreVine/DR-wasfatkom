<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            "doctor_id" => "required|integer|exists:users,id",
           // "price" => "required|numeric|min:0",
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            'code' => ['nullable', 'string', 'max:255'],
            "products" => ['required', 'array'],
            "products.*" => ['required', 'integer', 'exists:products,id', 'distinct'],
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
            $rules['image'] = str_replace("required", "nullable", $rules["image"]);
        }

        return $rules;
    }
}
