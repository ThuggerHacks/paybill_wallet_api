<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavingValidator extends FormRequest
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
            "amount_to_reach" => "required",
            "wallet_id" => "required"
        ];
    }

    public function messages() {

        return [
            "amount_to_reach.required" => "Por favor digite o valor",
            "wallet_id.required" => "Por favor informe a conta"
        ];
    }
}
