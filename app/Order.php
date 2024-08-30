<?php

namespace App;
use App\Helpers\Utilidade;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'swift_advance',
        'label',
        'owner',
        'owner_id',
        'signature_id',
        'unit_comission'
    ];

    protected $appends = [
        'full_packing',
        'packing',
        'containers_count'
    ];

    public function signature ()
    {
        return $this->hasOne(Signatures::class, 'id', 'signature_id');
    }

    public function bank ()
    {
        return $this->hasOne(Bank::class, 'id', 'banks_id')
            ->withDefault();
    }

    public function consignee ()
    {
        return $this->hasOne(User::class, 'id', 'consignee_id')
        ->withDefault();
    }

    public function items()
    {
        return $this->hasMany(\App\Item::class);
    }

    public function notify ()
    {
        return $this->hasOne(User::class, 'id', 'notify_id')
            ->withDefault();
    }

    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'owner_id')
            ->withDefault();
    }

    public function orders_document_order()
    {
        return $this->hasMany(\App\OrderDocumentOrder::class, 'orders_id', 'id');
    }
    public function specifications()
    {
        return $this->hasMany(\App\OrderSpecification::class, 'orders_id', 'id');
    }

    public function owner() {
        return $this->hasOne(\App\User::class, 'id', 'owner_id');
    }

    public function exporter() {
        return $this->hasOne(\App\User::class, 'id', 'exporter_id');
    }

    public function group() {
        return $this->hasOne(\App\Group::class, 'id', 'group_id');
    }

    public function documents () {
        return $this->hasMany(\App\Document::class);
    }

    public function transactions () {
        return $this->hasMany(\App\Transaction::class);
    }

    public function draft_documents () {
        return $this->hasOne(\App\DraftDocuments::class, 'order_id', 'id');
    }

    public function original_documents () {
        return $this->hasMany(\App\OriginalDocument::class);
    }

    public function order_status () {
        return $this->hasOne(\App\OrderStatus::class, 'id', 'order_status_id');
    }

    public function shipping () {
        return $this->hasMany(\App\Shipping::class);
    }

    public function fumigation () {
        return $this->hasMany(\App\Fumigation::class);
    }

    public function railroads () {
        return $this->hasOne(\App\Railroad::class);
    }

    public function loadings () {
        return $this->hasOne(\App\Loading::class);
    }

    public function bills () {
        return $this->hasMany(\App\Bill::class);
    }

    public function inspection_agency () {
        return $this->hasOne(\App\Inspection::class, 'order_id', 'id');
    }

    public function container_stuffing () {
        return $this->hasOne(\App\ContainerStuffing::class);
    }

    public function contract_provisions ()
    {
        return $this->hasMany(ContractProvision::class);
    }

    public function getImporterEmails(){
        $emails = [];

        if($this->owner){
            $emails[] = $this->owner->email;

            foreach ($this->owner->contacts()->whereIn('contact_type_id',[1,2])->get() as $contact) {
                if(Utilidade::isEmail($contact->value)){
                    $emails[] = $contact->value;
                }
            }
        }

        return array_unique($emails);
    }

    public function getExporterEmails(){
        $emails = [];

        if($this->exporter){
            $emails[] = $this->exporter->email;

            foreach (User::where('group_id',$this->exporter->group_id)
                    ->whereNull('deleted_at')
                    ->where('id','<>',$this->exporter->id)
                    ->whereHas("roles", function($q){
                        $q->where("name","=","Admin");
                    })
                    ->get() as $user
            ) {
                if(Utilidade::isEmail($user->email)){
                    $emails[] = $user->email;
                }
            }
        }

        return array_unique($emails);
    }


    public function certificateOfSeguro () {
        $drafts = $this->draft_documents()->get();
        foreach ($drafts as &$draft) {
            $temp_draft = array();
            $temp_draft['certificate_seguro']        = $draft->certificate_seguro;
            $temp_draft['certificate_seguro_reason'] = $draft->certificate_seguro_reason;
            $temp_draft['certificate_seguro_status'] = $draft->certificate_seguro_status;

            $draft = (object)$temp_draft;
        }
        return $drafts;
    }
    public function certificateOfFumigation () {
        $drafts = $this->draft_documents()->get();
        foreach ($drafts as &$draft) {
            $temp_draft = array();
            $temp_draft['certificate_fumigation']        = $draft->certificate_fumigation;
            $temp_draft['certificate_fumigation_reason'] = $draft->certificate_fumigation_reason;
            $temp_draft['certificate_fumigation_status'] = $draft->certificate_fumigation_status;

            $draft = (object)$temp_draft;
        }
        return $drafts;

    }

    public function certificateOfQuality () {
        $drafts = $this->draft_documents()->get();
        foreach ($drafts as &$draft) {
            $temp_draft = array();
            $temp_draft['certificate_seguro']        = $draft->certificate_seguro;
            $temp_draft['certificate_seguro_reason'] = $draft->certificate_seguro_reason;
            $temp_draft['certificate_seguro_status'] = $draft->certificate_seguro_status;

            $draft = (object)$temp_draft;
        }
        return $drafts;

    }

    public function certificateOfWeight () {
        $drafts = $this->draft_documents()->get();
        foreach ($drafts as &$draft) {
            $temp_draft = array();
            $temp_draft['certificate_seguro']        = $draft->certificate_seguro;
            $temp_draft['certificate_seguro_reason'] = $draft->certificate_seguro_reason;
            $temp_draft['certificate_seguro_status'] = $draft->certificate_seguro_status;

            $draft = (object)$temp_draft;
        }
        return $drafts;

    }

    public function inspectionAgency () {
        $drafts = $this->draft_documents()->get();
        foreach ($drafts as &$draft) {
            $temp_draft = array();
            $temp_draft['certificate_weight']        = $draft->certificate_weight;
            $temp_draft['certificate_weight_reason'] = $draft->certificate_weight_reason;
            $temp_draft['certificate_weight_status'] = $draft->certificate_weight_status;

            $temp_draft['certificate_quality']        = $draft->certificate_quality;
            $temp_draft['certificate_quality_reason'] = $draft->certificate_quality_reason;
            $temp_draft['certificate_quality_status'] = $draft->certificate_quality_status;

            $temp_draft['report']        = $draft->report;
            $temp_draft['report_reason'] = $draft->report_reason;
            $temp_draft['report_status'] = $draft->report_status;

            $draft = (object)$temp_draft;
        }
        return $drafts;

    }

    public function mapa () {
        return $this->hasOne(\App\Mapa::class, 'order_id', 'id');

    }

    public function forwarding_agent () {
        return $this->belongsTo(\App\User::class, 'forwarding_agent_id', 'id');
    }

    /**
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeFindById(Builder $query, int $id): Builder
    {
        return $query->where('id', $id);
    }

    /**
     * @param Builder $query
     * @param int $user
     * @return Builder
     */
    public function scopeFindByUser(Builder $query, int $user): Builder
    {
        return $query->where('user_id', $user);
    }

    public function getContainersCountAttribute()
    {
        if ($this->relationLoaded('shipping')) {
            return $this->shipping->sum(function ($shipping) {
                return $shipping->containers->count();
            });
        }
        return null;
    }

    public function getPackingAttribute()
    {
        if (isset($this->attributes['packing'])) {
            return strtoupper($this->attributes['packing']);
        }
        
        return null;
    }

    public function getFullPackingAttribute()
    {
        return $this->attributes['packing'] ?? null;
    }

}
