<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletPayValidator extends FormRequest
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
            "payment_amount" => "required",
            "payer_wallet_id" => "required",
            "secret_key" => "required|min:227|max:228"
        ];
    }

    public function messages() {
        return [
            "payment_amount.required" => "Por favor digite o valor a pagar",
            "payer_wallet_id.required" => "Por favor informe a sua carteira",
            "secret_key.required" => "Por favor informe a chave secreta",
            "secret_key.min" => "Chave invalida",
            "secret_key.max" => "Chave invalida"
        ];
    }
}
