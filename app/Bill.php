<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use SoftDeletes;
    

    public function vehicle() {
    	return $this->belongsTo(\App\Vehicle::class, 'truck_id', 'id');
    }
}
