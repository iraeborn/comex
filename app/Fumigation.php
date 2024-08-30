<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fumigation extends Model
{
    protected $fillable = [
        'order_id',
        'treatment_id',
        'date_of_fumigation_certificate',
        'date_of_conclusion',
        'treatment',
        'dosage',
        'exposition_time',
        'temperature_local',
        'place_of_treatment'
    ];

    public function order () {
        return $this->hasOne(\App\Order::class);
    }

    public function treatment () {
        return $this->hasOne(\App\Treatment::class);
    }

    public function observations () {
        return $this->hasMany(\App\FumigationObservation::class, 'fumigation_id', 'id');
    }
    
    public function draft_files () {
        $drafts = \App\DraftDocuments::where('order_id', $this->order_id)->first();
        if ($drafts)
            return $drafts->certificate_fumigation;

        return null;
    }
    
    public function original_files () {
        $original = \App\OriginalDocument::where('order_id', $this->order_id)->where('original_documents_type_id', 5)->first();
        if ($original)
            return $original->url;
        
        return null;
    }
}
