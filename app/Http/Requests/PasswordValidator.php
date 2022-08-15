<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordValidator extends FormRequest
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
            "user_password" => ["required"],
            "user_new_password" => ["min:6", "max:16","required","regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/"],
            "user_confirm_new_password" => ["required","same:user_new_password"],
        ];
    }

    public function messages() {
        return [
            "user_password.required" => "Por favor digite a senha atual",
            "user_new_password.min" => "A senha deve ter no minimo :min caracteres",
            "user_new_password.max" => "A senha deve ter no maximo :max caracteres",
            "user_new_password.required" => "A senha eh obrigatorio",
            "user_new_password.regex" => "A senha deve ter: pelomenos uma letra maiuscula, uma minuscula, um numero e um simbolo especial",
            "user_confirm_new_password.required" => "Por favorconfirme a senha",
            "user_confirm_new_password.same" => "A senha nao pode ser diferente.",
        ];
    }
}
