<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailValidator extends FormRequest
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
            'email' =>  ["max:70", "min:5", "required","email","regex:/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/"],
        ];
    }

    public function messages(){
        return [
            "user_email.required" => "O email eh obrigatorio",
            "user_email.email" => "O email eh invalido",
            "user_email.min" => "O email deve ter no minimo :min caracteres.",
            "user_email.max" => "O email deve ter no maximo :max caracteres",
            "user_email.regex" => "O email eh invalido",
        ];
    }
}
