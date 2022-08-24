<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentValidator extends FormRequest
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
            "payment_amount" => ["required"],
            "secret_key" => ["required","min:227","max:228"],
            "payer_wallet_id" => ["required","regex:/^2588(4|5)[0-9]{7}$/"]
        ];
    }

    public function messages(){

        return [
            "payer_wallet_id.regex" => "Invalid format, required: 25884xxxxxxx or 25885xxxxxxx",
            "payment_amount.required" => "Por favor digite o valor a pagar",
            "secret_key.required" => "Por favor informe a chave secreta",
            "secret_key.min" => "Chave invalida",
            "secret_key.max" => "Chave invalida"
        ];
    }
}
