<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Rules\CouponCodeRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                new CouponCodeRule,
                'max:255',
                Rule::unique('coupons', 'code')->ignore($this->coupon)
            ],
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after:from_date',
            'count_use' => 'nullable|integer|min:1',
            'percentage' => 'required|numeric|min:1|max:100',
            'doctors' => 'required|string'
        ];
    }
}
