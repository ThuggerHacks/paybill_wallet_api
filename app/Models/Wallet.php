<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Wallet extends Model
{
    use HasFactory;
    protected $table = "tbl_wallet";
    protected $primaryKey = "wallet_id";
    public $timestamps = false;
    protected $fillable = [
        "wallet_title",
        "wallet_money",
        "user_id",
        "wallet_activated_status",
        "wallet_associated_phone_number",
        "wallet_id"
    ];

    
    

}
