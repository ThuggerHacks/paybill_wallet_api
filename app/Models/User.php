<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "tbl_user";
    // protected $primaryKey = "user_id";
    protected $fillable = [
        "user_name",
        "user_email",
        "user_password",
        "user_phone_number",
        "user_birthdate",
        "user_birthplace",
        "user_id",
        "token",
        "pro_account",
        "user_profile"
    ];

    public $timestamps = false;

}
