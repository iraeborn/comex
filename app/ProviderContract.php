<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderContract extends Model
{

    use SoftDeletes;
    protected $table = "provider_contracts";

    protected $fillable = [
        'provider_id',
        'service_id',
        'identifier',
        'service_type',
        'service_location',
        'negotiated_terms',
        'is_active',
        'expirated_at',
    ];

    public function provider ()
    {
        return $this->hasOne(\App\User::class, 'id', 'provider_id');
    }

    public function service ()
    {
        return $this->hasOne(\App\Service::class, 'id', 'service_id');
    }

    public function contract_services ()
    {
        return $this->hasMany(ContractService::class, 'provider_contract_id', 'id');
    }

    public function contractProvision()
    {
        return $this->hasMany(ContractProvision::class, 'provider_contract_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id');
    }

}