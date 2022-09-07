<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $table = "pricing";
    protected $primaryKey = "pricing_id";
    public $timestamps = false;

    protected $fillable = [
        "pricing_title",
        "pricing_amount",
        "accounts_number"
    ];
}
