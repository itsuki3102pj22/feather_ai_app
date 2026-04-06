<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'season',
        'feather_type',
        'origin',
        'down_ratio',
        'contract_kg',
        'shipped_kg',
        'unit_price_jpy',
        ('comment'),
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 契約残
     public function getRemainingKgAttribute()
    {
        return $this->contract_kg - $this->shipped_kg;
    }

    // 進捗率
    public function getProgressRateAttribute()
    {
        if ($this->contract_kg == 0) return 0;
        return round($this->shipped_kg / $this->contract_kg * 100, 1);
    }

    // 契約金額合計
    public function getTotalAmountAttribute()
    {
        return $this->contract_kg * ($this->unit_price_jpy ?? 0);
    }

    // 出荷合計金額
    public function getShippedAmountAttribute()
    {
        return $this->shipped_kg * ($this->unit_price_jpy ?? 0);
    }
}

