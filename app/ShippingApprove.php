<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ShippingApprove extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'shipping_id',
        'vessel',
        'etd',
        'eta',
    ];

    protected $dates = [
        'eta',
        'etd',
    ];

    public function getEtaAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getEtdAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function shipping () {
        return $this->hasOne(\App\Shipping::class);
    }

    public function approved_by () {
        return $this->hasOne(\App\User::class);
    }
}
