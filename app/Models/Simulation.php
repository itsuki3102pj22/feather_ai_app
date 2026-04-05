<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $fillable = [
        'feather_type',
        'origin',
        'down_ratio',
        'feather_usd',
        'usd_jpy',
        'feather_jpy',
        'profit_rate',
        'sale_price_jpy',
        'customer_name',
        'comment',
    ];
}
