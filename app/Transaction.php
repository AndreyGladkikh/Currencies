<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'from_currency_id',
        'from_price',
        'to_currency_id',
        'to_price',
    ];
}
