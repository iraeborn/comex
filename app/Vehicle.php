<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $table = 'trucks';

    protected $appends = [
        'total_bags',
        'net_gross',
        'loading_location'
    ];

    protected $fillable = [
        'driver',
        'plate',
        'phone',
        'bill_number',
        'wheight',
        'estimated_time',
        'truck_unloading_datetime',
        'booking_key',
        'driver_id',
        'name_document',
        'tara_truck_one',
        'tara_truck_two',
        'tara_bags',
        'freight',
        'plate_two',
        'plate_three',
        'plate_four',
        'carrier_id',
        'provider_contract_id',
        'toll_payment',
        'note',
        'tara_horse',
        'renavam',
        'loading_number',
        'authorization',
        'loading_location_user_id'
    ];

    public function getTruckUnloadingDatetimeAttribute($date) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function getWheightAttribute()
    {
        return $this->attributes['wheight'] / 100;
        //return number_format($this->attributes['wheight'] / 100, 2, '.', ',');
    }
    public function getTotalBagsAttribute()
    {
        $this->bills()->each(function ($bag) use (&$totalBags){
            $totalBags += $bag->bags;
        });

        return $totalBags;
    }
    

    public function setWheightAttribute($value)
    {
        $value = preg_replace('/\,/', '', $value);
        $value = floatVal($value);
        $this->attributes['wheight'] = $value * 100;
    }

    public function getSealAttribute()
    {
        return number_format($this->attributes['seal'] / 100, 2, '.', ',');
    }

    public function drivers(){
        return $this->hasOne(\App\Driver::class, 'id', 'driver_id');
    }
    public function carriers(){
        return $this->hasOne(\App\User::class, 'id', 'carrier_id');
    }
    public function loading(){
        return $this->hasOne(\App\Loading::class, 'id', 'loadings_id');
    }

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'loading_location_user_id');
    }

    public function documents () {
        return $this->hasMany(\App\Document::class, 'entity_id', 'id');
    }

    public function bills () {
        return $this->hasMany(\App\Bill::class, 'truck_id', 'id');
    }

    public function setSealAttribute($value)
    {
        $this->attributes['seal'] = $value * 100;
    }
    // public function getNetGrossAttribute()
    // {
    //     $totalBags = 0;

    //     $loading = $this->loading;
        
    //     if(!isset($loading)){
    //         return null;
    //     }

    //     $order = $loading->order;

    //     if ($order && isset($order->packing)) {
    //         $packing = $order->packing;
            
    //         $this->bills->each(function ($bag) use (&$totalBags) {
    //             $totalBags += $bag->bags;
    //         });

    //         $netGrossValue = $this->wheight + ($this->wheight / $packing * $this->tara_bags);

    //         return number_format($netGrossValue, 2, ",", ".");
    //     }

    //     return null;
    // }

    // public function getNetGrossAttribute()
    // {
    //     $totalBags = 0;

    //     foreach ($this->bills as $bag) {
    //         if (is_object($bag) && isset($bag->bags)) {
    //             $totalBags += $bag->bags;
    //         }
    //     }

    //     $loading = $this->loading;
    //     $order = $loading->order;

    //     if ($order && isset($order->packing)) {
    //         $packing = $order->packing ?? 1;
    //         $wheight = $this->wheight ?? 1;
    //         $tara_bags = $this->tara_bags ?? 1;

    //         if ($packing != 0 && $wheight != 0 && $tara_bags != 0) {
    //             $netGrossValue = $wheight + ($wheight / $packing * $tara_bags);
    //         } else {
    //             $netGrossValue = 0;
    //         }

    //         return number_format($netGrossValue, 2, ",", ".");
    //     }

    //     return null;
    // }

    public function getNetGrossAttribute()
    {
        $totalBags = 0;

        foreach ($this->bills as $bag) {
            if (is_object($bag) && isset($bag->bags) && is_numeric($bag->bags)) {
                $totalBags += $bag->bags;
            }
        }

        $loading = $this->loading;
        $order = $loading->order;

        if ($order && isset($order->packing)) {
            $packing = is_numeric($order->packing) ? $order->packing : 1;
            $wheight = is_numeric($this->wheight) ? $this->wheight : 1;
            $tara_bags = is_numeric($this->tara_bags) ? $this->tara_bags : 1;

            if ($packing != 0 && $wheight != 0 && $tara_bags != 0) {
                $netGrossValue = $wheight + ($wheight / $packing * $tara_bags);
            } else {
                $netGrossValue = 0;
            }

            return number_format($netGrossValue, 2, ",", ".");
        }

        return null;
    }


    public function getLoadingLocationAttribute()
    {

            $user = User::find($this->loading_location_user_id);
        
            if ($user) {
                $name = $user->name;
            } else {
                $name = 'Terminal agent not set';
            }

        
        return $name;        
    }

    
}
