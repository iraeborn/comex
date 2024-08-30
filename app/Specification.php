<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{

    protected $table = "specifications";

    protected $fillable = [
        'id',
        'description'
    ];

    public function group()
    {
        return $this->hasOne(\App\Group::class, 'id', 'group_id');
    }
   
}
