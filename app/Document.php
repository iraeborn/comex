<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    // use SoftDeletes;

    protected $fillable = ['document_status_id'];

    public function order () {
        return $this->hasOne(\App\Order::class, 'id', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function signature()
    {
        return $this->hasOne(Signatures::class, 'id', 'signature_id');
    }

        public function document_order()
    {
        return $this->hasMany(\App\DocumentOrder::class, 'orders_id', 'id');
    }
}
