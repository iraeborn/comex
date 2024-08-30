<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class DraftDocuments extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'draft_bl',
        'draft_comercial',
        'packing_list',
        'certificate_origin',
        'certificate_fumigation',
        'certificate_quality',
        'certificate_weight',
        'certificate_seguro',
        'certificate_phyto',
        'report',
        'health_certificate',
        'non_gmo_certificate',
        'others',

        'draft_bl_reason',
        'draft_comercial_reason',
        'packing_list_reason',
        'certificate_origin_reason',
        'certificate_fumigation_reason',
        'certificate_quality_reason',
        'certificate_weight_reason',
        'certificate_seguro_reason',
        'certificate_phyto_reason',
        'report_reason',
        'health_certificate_reason',
        'non_gmo_certificate_reason',
        'others_reason',

        'draft_bl_status',
        'draft_comercial_status',
        'packing_list_status',
        'certificate_origin_status',
        'certificate_fumigation_status',
        'certificate_quality_status',
        'certificate_weight_status',
        'certificate_seguro_status',
        'certificate_phyto_status',
        'report_status',
        'health_certificate_status',
        'non_gmo_certificate_status',
        'others_status'
    ];

    public function order () {
        return $this->hasOne(\App\Order::class);
    }
}
