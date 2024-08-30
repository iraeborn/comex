<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractService extends Model
{

    use SoftDeletes;
    protected $table = "contract_services";
    protected $appends = ['factor_display_name'];

    protected $fillable = [
        'provider_contract_id',
        'description',
        'billing_factor_type',
        'currency_type',
        'billing_value',
    ];

    public function provider_contract ()
    {
        return $this->belongsTo(ProviderContract::class);
    }

    public function getBillingValueAttribute ($value)
    {
        if ($value > 0) {
            return $value / 10000;
        }

        return $value;

    }

    public function getFactorDisplayNameAttribute ()
    {

        $factorIndex = $this->billing_factor_type;
        $factorTypes = [
            1 => 'per conteiner',
            2 => 'per conteiner (dolar day)',
            3 => 'per ton',
            4 => 'per order',
        ];

        if ( ! isset ($factorTypes[$factorIndex]) ) {
            return "undefined";
        }

        return $factorTypes[$factorIndex];
    }

}