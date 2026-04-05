<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRecord extends Model
{
    protected $fillable = [
        'record_date',
        'period_type',
        'usd_jpy',
        'white_duck_usd',
        'white_duck_jpy',
        'grey_duck_jpy',
        'ai_comment',
        'manual_comment',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];
}
