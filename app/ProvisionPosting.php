<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProvisionPosting extends Model
{
    use SoftDeletes;
    protected $table = "provision_postings";

    protected $fillable = [
        'contract_provision_id',
        'invoice_id',
        'service_type',
        'invoice_amount',
        'invoice_amount_converted',
        'currency_fee',
        'currency_type',
        'due_date',
    ];

    public function getInvoiceAmountAttribute ($value)
    {
        if ($value > 0) {
            return $value / 100;
        }

        return $value;

    }

    public function getInvoiceAmountConvertedAttribute ($value)
    {
        if ($value > 0) {
            return $value / 100;
        }

        return $value;

    }

    public function getCurrencyFeeAttribute ($value)
    {
        if ($value > 0) {
            return $value / 100;
        }

        return $value;

    }

    public function contract_provision ()
    {
        return $this->belongsTo(ContractProvision::class);
    }

    public function foreignAccount ()
    {
        return $this->hasOne(ForeignAccountPosting::class);
    }

}