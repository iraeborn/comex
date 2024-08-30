<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use SoftDeletes;

    protected $table = 'order_status';
}
