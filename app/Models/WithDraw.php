<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithDraw extends Model
{
    use HasFactory;
    protected $table = "tbl_withdraw";
    protected $primaryKey = "withdraw_reference";
    public $timestamps = false;

    protected $fillable = [
        "withdraw_amount",
        "withdraw_wallet_id",
        "withdraw_reference"
    ];

}
