<?php

namespace App\Actions\Order;

use App\Order;

class UpdateProviderAction
{
    public function __invoke(int $order_id, string $providerKey, int $providerValue)
    {
        $order = Order::find($order_id);

        $order->{$providerKey} = $providerValue;
        $order->save();
    }
}