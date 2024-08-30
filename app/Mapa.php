<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Mapa extends Model
{
    use SoftDeletes;
        
    protected $fillable = [];

    protected $dates = [
        'inspection_date',
        'date_currency_fee'
    ];

    public function getInspectionDateAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getDateCurrencyFeeAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function observations () {
        return $this->hasMany(\App\MapaObservation::class, 'mapa_id', 'id');
    }

    public function getCurrencyFeeAttribute()
    {
        return $this->attributes['currency_fee'] / 10000;
    }

    public function setCurrencyFeeAttribute($value)
    {
        $this->attributes['currency_fee'] = $value * 10000;
    }

}
