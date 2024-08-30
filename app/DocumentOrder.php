<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class DocumentOrder extends Model
{

    protected $table = "document_order";

    protected $fillable = [
        'id',
        'description'
    ];

    public function group()
    {
        return $this->hasOne(\App\Group::class, 'id', 'group_id');
    }
   
}
