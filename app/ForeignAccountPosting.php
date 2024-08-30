<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignAccountPosting extends Model
{

    use SoftDeletes;
    protected $table = "foreign_account_postings";

    protected $fillable = [
        'invoice_id',
        'supplier_id',
        'provision_posting_id',
        'description',
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

    public function supplier ()
    {
        return $this->belongsTo(User::class);
    }

}