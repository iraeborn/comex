<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryLog extends Model
{

    protected $fillable = ['log', 'causer_id', 'updated_at', 'created_at'];

}
