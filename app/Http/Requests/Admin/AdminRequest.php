<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //      return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => "required|string|max:255",
            "email" => ["required", "email:filter", Rule::unique('users', 'email')->ignore($this->admin)],
            "password" => "required|string|min:8",
            "password_confirmation" => "required|string|same:password",
            "permissions" => "nullable|array",
            "permissions.*" => "required|string|exists:permissions,name|not_in:admins",
            "is_all_doctor" => 'nullable',
            "doctors" => "nullable|string",
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['password'] = str_replace("required", "nullable", $rules["password"]);
            $rules['password_confirmation'] = str_replace("required", "nullable", $rules["password"]);
        }

        return $rules;
    }
}
