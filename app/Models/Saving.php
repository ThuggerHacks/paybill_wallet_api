<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;
    protected $table = "tbl_saving";
    protected $fillable = [
        "wallet_id",
        "amount_to_reach",
        "wallet_status"
    ];
   
}
