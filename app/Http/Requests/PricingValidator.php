<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "pricing_title" => ["required"],
            "pricing_amount" => ["required"],
            "accounts_number" => ["required"]
        ];
    }
}
