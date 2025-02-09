<?php

namespace App\Rules;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CouponCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if (!preg_match('/^\S*$/u', $value)) {
            $fail(__("messages.English letters and numbers only, no spaces"));
        }

        // check only letter english AND number
        if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) {
            $fail(__("messages.English letters and numbers only, no spaces"));
        }
    }
}
