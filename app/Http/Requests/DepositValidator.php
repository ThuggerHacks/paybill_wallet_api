<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositValidator extends FormRequest
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
            "from" => ["required"],
            "to" => "required"
        ];
    }

    public function messages(){
        return [
            "amount.required" => "Por favor digite o valor",
            "from.required" => "Por favor informe digite o seu numero de conta",
            "to.required" => "Por favor informe o destino"
        ];
    }
}
