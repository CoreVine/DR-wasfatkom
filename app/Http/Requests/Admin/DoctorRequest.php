<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
        $rules = [];
        $rules = [
            'name' => 'required', 'string', 'max:255',
            'clinic_name' => 'required', 'string', 'max:255',
            'email' => ['required', 'email:filter', Rule::unique('users', 'email')->ignore($this->doctor)],
            'password' => "required|string|min:8",
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            "password_confirmation" => "required|string|same:password",
            'mobile' => 'nullable', 'string', 'max:11', Rule::unique('users', 'mobile')->ignore($this->doctor),
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['password'] = str_replace("required", "nullable", $rules["password"]);
            $rules['password_confirmation'] = str_replace("required", "nullable", $rules["password"]);
        }

        return $rules;
    }
}
