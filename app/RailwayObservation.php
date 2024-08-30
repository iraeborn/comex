<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class RailwayObservation extends Model
{
    use SoftDeletes;
        
    public function railway () {
        return $this->hasOne(\App\Railroad::class);
    }
    
}
