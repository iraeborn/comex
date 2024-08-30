<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use SoftDeletes;

    const INITIAL_BALANCE = 1;
    const ADVANCED = 2;
    const EXCHANGE = 3;
    const BANKERS_COMMISSION = 4;
    const BALANCE = 5;
    const SUPPLIER_PAYMENT = 6;
    const FOREIGN_ACCOUNT = 7;
    const AVAILABLE = 8;
    const ADDITIONAL_BILL = 9;
    const AVAILABLE_ADVANCE = 10;

    public function transaction_type()
    {
        return $this->hasOne(TransactionType::class, 'id', 'transaction_type_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function setValueAttribute($value)
    {
        //$newValue = preg_replace('/[\.,]/', '', $value);

        //dd(!preg_match('/[\.,]\d{2}$/', $value));
        //if (!preg_match('/[\.,]\d{2}$/', $value)) {
        //    $newValue = $newValue * 100;
        //}

        $newValue = floatval($value) * 100;

        $this->attributes['value'] = $newValue;
    }

    public function setDollarValueAttribute($value)
    {
        if (!$value) {
            $this->attributes['dollar_value'] = 0;
            return;
        }

        $newValue = preg_replace('/[\,]/', '', $value);

        $newValue = floatval($newValue);

        /*if (!preg_match('/[\.,]\d{2}$/', $value)) {
            $newValue = $newValue * 100;
        }*/

        $this->attributes['dollar_value'] = $newValue;
    }

    public function setConvertedValueAttribute($value)
    {
        if (!$value) {
            $this->attributes['converted_value'] = 0;
            return;
        }

        $newValue = preg_replace('/[\.,]/', '', $value);

        if (!preg_match('/[\.,]\d{2}$/', $value)) {
            $newValue = $newValue * 100;
        }

        $this->attributes['converted_value'] = $newValue;
    }

    public function getValueAttribute($value)
    {
        return $value / 100;
    }

    public function getDollarValueAttribute($value)
    {
        return floatval($value);
    }

    public function getConvertedValueAttribute($value)
    {
        return $value / 100;
    }

    public function getDataAttribute($value)
    {
        if (!$value) return '';
        $data = preg_split('/\s/', $value);
        return $data[0];
    }

    public static function getStatus($order, $transaction_value)
    {
        if (!$order->expiry_date) return 'Expiration date is not defined';

        $expir_date = \DateTime::createFromFormat('Y-m-d H:i:s', $order->expiry_date);
        $today = new \DateTime();

        $diff = $expir_date->diff($today);

        if ($transaction_value == 0) return 'Paid';

        if (!$diff->invert)
            return 'Expired';

        return 'Amount Yet Due';
    }
}
