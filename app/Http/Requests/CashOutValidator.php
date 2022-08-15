<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashOutValidator extends FormRequest
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
            "amount" => "required",
            "from" => "required"
        ];
    }

    public function messages(){

        return [
            "amount.required" => "Por favor informe o valor a sacar.",
            "from.required" => "Por favor informe a conta"
        ];
    }
}
