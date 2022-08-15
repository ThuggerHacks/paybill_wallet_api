<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidator extends FormRequest
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
            "user_name" => ["max:100", "min:3", "required"],
            "user_email" => ["max:70", "min:5", "required","email","regex:/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/"],
            "user_password" => ["min:6", "max:16","required","regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/"],
            "user_confirm_password" => ["required","same:user_password"],
            "user_phone_number" => ["min:9","max:15","required","regex:/^(84|85|86|87|82|83)[0-9]{7}$/"],
            "user_birthdate" => ["min:8","max:10","required"],
            "user_birthplace" => ["min:2","max:50","required"],
        ];
    }


    public function messages(){
        return [
            "user_name.min" => "O nome deve ter no minimo :min caracteres",
            "user_name.max" => "O nome deve ter no maximo :max caracteres",
            "user_name.required" => "O nome eh obrigatorio.",
            "user_email.required" => "O email eh obrigatorio",
            "user_email.email" => "O email eh invalido",
            "user_email.min" => "O email deve ter no minimo :min caracteres.",
            "user_email.max" => "O email deve ter no maximo :max caracteres",
            "user_email.regex" => "O email eh invalido",
            "user_password.min" => "A senha deve ter no minimo :min caracteres",
            "user_password.max" => "A senha deve ter no maximo :max caracteres",
            "user_password.required" => "A senha eh obrigatorio",
            "user_password.regex" => "A senha deve ter: pelomenos uma letra maiuscula, uma minuscula, um numero e um simbolo especial",
            "user_confirm_password.required" => "Por favorconfirme a senha",
            "user_confirm_password.same" => "A senha nao pode ser diferente.",
            "user_phone_number.min" => "O numero deve ter no minimo :min caracteres.",
            "user_phone_number.max" => "O numero deve ter no maximo :max caracteres.",
            "user_phone_number.required" => "O numero deve ser preenchido.",
            "user_phone_number.regex" => "Numero invalido",
            "user_birthplace.min" => "O local de nascimento deve ter no minimo :min caracteres.",
            "user_birthplace.max" => "O local de nascimento deve ter no maximo :max caracteres.",
            "user_birthplace.required" => "Por favor preencha o local de  nascimento",
            "user_birthdate.min" => "A data de nascimento deve ter no minimo :min caracteres.",
            "user_birthdate.max" => "A data de nascimento deve ter no maximo :max caracteres.",
            "user_birthdate.required" => "Por favor preencha a data de  nascimento",

        ];
    }
}
