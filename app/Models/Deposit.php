<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = "tbl_deposit";
    protected $primaryKey = "deposit_reference";
    public $timestamps = false;

    protected $fillable = [
        "deposit_amount",
        "deposit_from",
        "deposit_to_wallet_id",
        "deposit_reference"
    ];

}
