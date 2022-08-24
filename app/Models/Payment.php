<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = "tbl_payment";
    protected $primaryKey = "payment_reference";
    public $timestamps = false;

    protected $fillable = [
        "payment_reference",
        "payment_title",
        "payment_amount",
        "wallet_id",
        "payer_wallet_id"
    ];
}
