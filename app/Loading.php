<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Loading extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'start_truck_loading_date',
        'end_truck_loading_date',
        'loading_location',
        'unloading_location',
        'transit_time',
        'tax_ptax',
        'date_ptax',
        'address'
    ];

    protected $dates = [
        'start_truck_loading_date',
        'end_truck_loading_date',
    ];

    protected $appends = [
        'loading_location_user'
    ];

    public function getStartTruckLoadingDateAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getEndTruckLoadingDateAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
    public function getDatePTAX($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function vehicles () {
        return $this->hasMany(\App\Vehicle::class, 'loadings_id', 'id');
    }

    public function order () {
        return $this->hasOne(\App\Order::class, 'id', 'order_id');
    }

    public function getLoadingLocationUserAttribute()
    {
        $order = $this->order()->first();
        // dd($order);

        if ($order) {
            $terminal_agent_id = $order->terminal_agent_id;
            // dd($terminal_agent_id);

            $user = User::find($terminal_agent_id);
        
            if ($user) {
                $name = $user->name;
            } else {
                $name = 'Terminal agent not set';
            }
        } else {
            $name = 'Ordem not found';
        }
        
        return $name;        
    }
}
