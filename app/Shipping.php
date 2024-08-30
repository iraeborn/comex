<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking',
        'discharge_port_id',
        'eta',
        'etd',
        'free_time_origin',
        'free_time_destination',
        'loading_port_id',
        'shipping_line',
        'vessel',
        'quantity_containers',
        'draft_deadline',
        'loading_deadline',
        'freight'
    ];

    protected $dates = [
        'eta',
        'etd',
        'draft_deadline',
        'loading_deadline',
    ];

    /*public function setEtaAttribute($date) {
        $this->attributes['eta'] = \Carbon\Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
    }

    public function setEtdAttribute($date) {
        $this->attributes['etd'] = \Carbon\Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
    }*/

    public function getEtaAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getEtdAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getDraftDeadlineAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getLoadingDeadlineAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function order () {
        return $this->hasOne(\App\Order::class, 'id', 'order_id');
    }

    public function containers () {
        return $this->hasMany(\App\Container::class);
    }

    public function approves () {
        return $this->hasMany(\App\ShippingApprove::class);
    }

    public function loading_port ()
    {
        return $this->belongsTo(Port::class);
    }

    public function discharge_port ()
    {
        return $this->belongsTo(Port::class);
    }

    public function status () {
        return $this->hasMany(\App\ShippingStatus::class, 'id', 'shipping_status_id');
    }
}
