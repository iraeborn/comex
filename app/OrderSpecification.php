<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class OrderSpecification extends Model
{

    protected $table = "orders_specifications";

    protected $fillable = [
        'id',
        'orders_id',
        'specifications_id'
    ];

    
    public function saveOrderSpecifications($specification, $orderId){

            $ordersSpecifications = new OrderSpecification();
            $ordersSpecifications->where('orders_id', $orderId)->delete();

        
            $ordersSpecifications->create(
        [
                'orders_id' => $orderId,
                'specifications_id' => $specification,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
                ]
            );
    }

    public function specification () {
        return $this->belongsTo(Specification::class, 'specifications_id', 'id');
    }

}
