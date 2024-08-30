<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractProvision extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = "contract_provisions";

    protected $fillable = [
        'provider_contract_id',
        'order_id',
        'truck_id',
        'budgeted_amount',
        'effective_amount',
        'currency_type',
        'status',
        'service_provision_id',
    ];

    protected $appends = [
        'provider_id'
    ];

    public function getProviderIdAttribute()
    {
        return ProviderContract::find($this->provider_contract_id)->provider_id;
    }
    public function order ()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function vehicle ()
    {
        return $this->belongsTo(Vehicle::class, 'truck_id');
    }

    public function provider_contract ()
    {
        return $this->belongsTo(ProviderContract::class);
    }

    public function provision_posting ()
    {
        return $this->hasMany(ProvisionPosting::class);
    }

}