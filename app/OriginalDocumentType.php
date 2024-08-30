<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OriginalDocumentType extends Model
{
    public const CONTRACT = 1;
    public const CONTRACT_SIGNED = 2;
    public const PROFORMA_INVOICE = 3;
    public const PROFORMA_INVOICE_SIGNED = 4;
    public const SWIFT_ADVANCED = 5;
    public const DOCUMENTS_INSTRUCTIONS = 6;
    public const LABEL_MODEL = 7;
    public const HEALTH_CERTIFICATE = 8;
    public const ORDER = 9;
    public const SHIPPING = 10;
    protected $table = "original_documents_type";
}
