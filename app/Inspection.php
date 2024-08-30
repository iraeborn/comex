<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use SoftDeletes;

    public function order () {
        return $this->hasOne(\App\Order::class, 'id', 'order_id');
    }

    public function observations () {
        return $this->hasMany(\App\InspectionObservation::class, 'inspection_id', 'id');
    }
}
