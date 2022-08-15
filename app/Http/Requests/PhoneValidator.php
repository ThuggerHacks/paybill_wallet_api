<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneValidator extends FormRequest
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
            "wallet_associated_phone_number" => ["required","regex:/^(84|85)[0-9]{7}$/"]
        ];
    }

    public function messages(){

        return [
            "wallet_associated_phone_number.required" => "Por favor digite o seu celular.",
            "wallet_associated_phone_number.regex" => "Celular invalido"
        ];
    }
}
