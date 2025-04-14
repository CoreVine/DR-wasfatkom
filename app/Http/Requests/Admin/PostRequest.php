<?php

namespace App\Http\Requests\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $rules=[];

        foreach($languages as $lang){
            if($lang->is_default){
                $rules["title_$lang->code"]="required|string|max:255";
                $rules["slug_$lang->code"]="required|string|max:255";
                $rules["content_$lang->code"]="required|string|max:300000";
                $rules["meta_description_$lang->code"]="required|string|max:150";
            }else{
                $rules["title_$lang->code"]="nullable|string|max:255";
                $rules["slug_$lang->code"]="nullable|string|max:255";
                $rules["content_$lang->code"]="nullable|string|max:300000";
                $rules["meta_description_$lang->code"]="nullable|string|max:150";
            }
        }


        return $rules;
    }
}
