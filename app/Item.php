<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use SoftDeletes;
    
    public function getQuantityAttribute()
    {
        return $this->attributes['quantity'] / 100;
    }

    public function setQuantityAttribute($value)
    {
        $value = floatval($value);
        $this->attributes['quantity'] = $value * 100;
    }

    public function getUnitPriceAttribute()
    {
        return $this->attributes['unit_price'] / 100;
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = $value * 100;
    }

    public function getTotalPriceAttribute()
    {
        return $this->attributes['total_price'] / 100;
    }

    public function setTotalPriceAttribute($value)
    {
        $this->attributes['total_price'] = $value * 100;
    }
    
    public function getTotalBagsAttribute()
    {
        return $this->attributes['total_bags'] / 100;
    }

    public function setTotalBagsAttribute($value)
    {
        $this->attributes['total_bags'] = $value * 100;
    }
    
    public function getNetWeightAttribute()
    {
        return $this->attributes['net_weight'] / 100;
    }

    public function setNetWeightAttribute($value)
    {
        $this->attributes['net_weight'] = $value * 100;
    }
    
    public function getGrossWeightAttribute()
    {
        return $this->attributes['gross_weight'] / 100;
    }

    public function setGrossWeightAttribute($value)
    {
        $this->attributes['gross_weight'] = $value * 100;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
