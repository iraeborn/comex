<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    public const IN_ANALYSIS = 1;
    public const REJECTED = 2;
    public const APROVED = 3;

    protected $table = "document_status";
}
