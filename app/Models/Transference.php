<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transference extends Model
{
    use HasFactory;
    protected $table = "tbl_sent";
    protected $primaryKey = "sent_reference";
    public $timestamps = false;

    protected $fillable = [
        "sent_amount",
        "sent_from_wallet_id",
        "sent_to_wallet_id",
        "sent_reference"
    ];

}
