<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'from_currency_id',
        'from_price',
        'to_currency_id',
        'to_price',
    ];
}
