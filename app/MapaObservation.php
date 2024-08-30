<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class MapaObservation extends Model
{
    use SoftDeletes;
        
    public function mapa () {
        return $this->hasOne(\App\Mapa::class);
    }
    
}
