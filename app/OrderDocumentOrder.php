<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class OrderDocumentOrder extends Model
{

    protected $table = "orders_document_order";

    protected $fillable = [
        'id',
        'orders_id',
        'document_order_id'
    ];

    public function document_order() {
        return $this->hasOne(\App\DocumentOrder::class, 'id', 'document_order_id');
    }

}
