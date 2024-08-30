<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class ContainerStuffing extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'terminal',
        'terminal_user',
        'weight',
        'number_of_bags',
        'empty_release_for_outbound_date',
        'stuffing_starting_date',
        'stuffing_ending_date',
        'containers_return_date',
        'terminal_observations',
        
        'depot_pick',
        'qtd_container',
        'quantity_per_container',
        'quantity_total',
        'container_type',
        'dispatch_place_name',
        'dispatch_place_code',
    ];

    protected $dates = [
        'empty_release_for_outbound_date',
        'stuffing_starting_date',
        'stuffing_ending_date',
        'containers_return_date'
    ];

    public function getEmptyReleaseForOutboundDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getStuffingStartingDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getStuffingEndingDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getContainersReturnDateAttribute ( $date ) {
        if(!$date) return null;
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }

    public function getWeightAttribute()
    {
        //return number_format(, 2, '.', ',');
        return $this->attributes['weight'] / 100;
    }

    public function setWeightAttribute($value)
    {
        $value = floatval(preg_replace('/[^\d.]+/', '', $value));
        $this->attributes['weight'] = $value * 100;
    }

    public function observations () {
        return $this->hasMany(\App\ContainerObservation::class, 'container_id', 'id');
    }
}
