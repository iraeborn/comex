<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class OriginalDocument extends Model
{
    use SoftDeletes;

    public function order () {
        return $this->hasOne(\App\Order::class, 'id', 'order_id');
    }

    public function document_type () {
        return $this->hasOne(\App\OriginalDocumentType::class, 'id', 'original_documents_type_id');
    }

    public function document_status () {
        return $this->hasOne(\App\DocumentStatus::class, 'id', 'original_documents_status_id');
    }
}
