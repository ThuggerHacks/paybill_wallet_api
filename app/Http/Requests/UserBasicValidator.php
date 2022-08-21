<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserBasicValidator extends FormRequest
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
            "user_name" => ["max:100", "min:3", ],
            "user_email" => ["max:70", "min:5", "email","regex:/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/"],
            "user_phone_number" => ["min:9","max:15","regex:/^(84|85|86|87|82|83)[0-9]{7}$/"]
        ];
    }

    public function messages(){

        return [
            "user_phone_number.regex" => "Numero invalido",
            "user_name.min" => "O nome deve ter no minimo :min caracteres",
            "user_name.max" => "O nome deve ter no maximo :max caracteres",
            "user_name.required" => "O nome eh obrigatorio.",
            "user_email.required" => "O email eh obrigatorio",
            "user_email.email" => "O email eh invalido",
            "user_email.min" => "O email deve ter no minimo :min caracteres.",
            "user_email.max" => "O email deve ter no maximo :max caracteres",
            "user_email.regex" => "O email eh invalido",
            "user_phone_number.min" => "O numero deve ter no minimo :min caracteres.",
            "user_phone_number.max" => "O numero deve ter no maximo :max caracteres.",
            "user_phone_number.required" => "O numero deve ser preenchido.",
        ];
    }
}
