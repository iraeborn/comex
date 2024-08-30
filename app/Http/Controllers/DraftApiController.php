<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\OrderDraftDocumentsJob;
use App\Jobs\OrderFumigationJob;

class DraftApiController extends Controller
{
    public function index ( \Request $request ) {
        $orders = \App\Order::all();

        foreach ($orders as $order) {
            $order->draft_documentos = $order->draft_documents();
        }

        return response()->json($orders);
    }

    public function get ( \Request $request, $id ) {
        $order = \App\Order::find($id);

        $drafts = $order->draft_documents()->first();

        if(!$drafts) {
            $drafts = new \App\DraftDocuments();
            $drafts->order_id = $id;
            //$drafts->save();
        }

        //$drafts = $drafts[0];
        //dd($drafts);
        //$drafts = array_shift($drafts);

        //$drafts->allow_access = json_decode($drafts->allow_access);
        //dd($drafts);
        // if(!$drafts) $drafts = new \App\DraftDocuments();

        try {
            $drafts->allowAccess = json_decode($drafts->allow_access);
        } catch (\Exception $e) {
            $drafts->allowAccess = null;
        }

        if(!$drafts->allowAccess) {
            $drafts->allowAccess = (object)[];
        }

        if(!property_exists($drafts->allowAccess,'draft_bl'))
            $drafts->allowAccess->draft_bl = [];

        if(!property_exists($drafts->allowAccess,'draft_comercial'))
            $drafts->allowAccess->draft_comercial = [];

        if(!property_exists($drafts->allowAccess,'packing_list'))
            $drafts->allowAccess->packing_list = [];

        if(!property_exists($drafts->allowAccess,'certificate_origin'))
            $drafts->allowAccess->certificate_origin = [];

        if(!property_exists($drafts->allowAccess,'certificate_fumigation'))
            $drafts->allowAccess->certificate_fumigation = [];

        if(!property_exists($drafts->allowAccess,'certificate_quality'))
            $drafts->allowAccess->certificate_quality = [];

        if(!property_exists($drafts->allowAccess,'certificate_weight'))
            $drafts->allowAccess->certificate_weight = [];

        if(!property_exists($drafts->allowAccess,'certificate_seguro'))
            $drafts->allowAccess->certificate_seguro = [];

        if(!property_exists($drafts->allowAccess,'certificate_phyto'))
            $drafts->allowAccess->certificate_phyto = [];

        if(!property_exists($drafts->allowAccess,'report_status'))
            $drafts->allowAccess->report_status = [];

        if(!property_exists($drafts->allowAccess,'health_certificate'))
            $drafts->allowAccess->health_certificate = [];

        if(!property_exists($drafts->allowAccess,'non_gmo_certificate'))
            $drafts->allowAccess->non_gmo_certificate = [];

        $drafts->draft_bl_access = false;
        $drafts->draft_comercial_access = false;
        $drafts->packing_list_access = false;
        $drafts->certificate_origin_access = false;
        $drafts->certificate_fumigation_access = false;
        $drafts->certificate_quality_access = false;
        $drafts->certificate_weight_access = false;
        $drafts->certificate_seguro_access = false;
        $drafts->certificate_phyto_access = false;
        $drafts->report_status_access = false;
        $drafts->health_certificate_access = false;
        $drafts->non_gmo_certificate_access = false;

        $role_id = -1;

        switch (\Request::get('user_id')) {
            case $order->owner_id:
                $role_id = 2;
                break;
            case $order->fumigation_id:
                $role_id = 3;
                break;
            case $order->quality_id:
                $role_id = 4;
                break;
            case $order->weight_id:
                $role_id = 5;
                break;
            case $order->seguro_id:
                $role_id = 6;
                break;
            case $order->inspection_id:
                $role_id = 7;
                break;
            case $order->forwarding_agent_id:
                $role_id = 8;
                break;
        }

        if(in_array($role_id, $drafts->allowAccess->draft_bl))
            $drafts->draft_bl_access = true;

        if(in_array($role_id, $drafts->allowAccess->draft_comercial))
            $drafts->draft_comercial_access = true;

        if(in_array($role_id, $drafts->allowAccess->packing_list))
            $drafts->packing_list_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_origin))
            $drafts->certificate_origin_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_fumigation))
            $drafts->certificate_fumigation_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_quality))
            $drafts->certificate_quality_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_weight))
            $drafts->certificate_weight_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_seguro))
            $drafts->certificate_seguro_access = true;

        if(in_array($role_id, $drafts->allowAccess->certificate_phyto))
            $drafts->certificate_phyto_access = true;

        if(in_array($role_id, $drafts->allowAccess->report_status))
            $drafts->report_status_access = true;

        if(in_array($role_id, $drafts->allowAccess->health_certificate))
            $drafts->health_certificate_access = true;

        if(in_array($role_id, $drafts->allowAccess->non_gmo_certificate))
            $drafts->non_gmo_certificate_access = true;



        try {
            $drafts->required = json_decode($drafts->required);
        } catch (\Exception $e) {
            $drafts->required = null;
        }

        if(!$drafts->required) {
            $drafts->required = (object)[];
        }

        if(!property_exists($drafts->required,'draft_bl'))
            $drafts->required->draft_bl = true;

        if(!property_exists($drafts->required,'draft_comercial'))
            $drafts->required->draft_comercial = true;

        if(!property_exists($drafts->required,'packing_list'))
            $drafts->required->packing_list = true;

        if(!property_exists($drafts->required,'certificate_origin'))
            $drafts->required->certificate_origin = true;

        if(!property_exists($drafts->required,'certificate_fumigation'))
            $drafts->required->certificate_fumigation = true;

        if(!property_exists($drafts->required,'certificate_quality'))
            $drafts->required->certificate_quality = true;

        if(!property_exists($drafts->required,'certificate_weight'))
            $drafts->required->certificate_weight = true;

        if(!property_exists($drafts->required,'certificate_seguro'))
            $drafts->required->certificate_seguro = true;

        if(!property_exists($drafts->required,'certificate_phyto'))
            $drafts->required->certificate_phyto = true;

        if(!property_exists($drafts->required,'report'))
            $drafts->required->report = true;

        if(!property_exists($drafts->required,'health_certificate'))
            $drafts->required->health_certificate = true;

        if(!property_exists($drafts->required,'non_gmo_certificate'))
            $drafts->required->non_gmo_certificate = true;

        if(!property_exists($drafts->required,'others'))
            $drafts->required->others = 0;


        if ($drafts) {
            try {
                $drafts->others        = json_decode( $drafts->others );
            } catch (\Exception $e) {
                $drafts->others = (object)[];
            }
            try {
                $drafts->others_status = json_decode( $drafts->others_status );
            } catch (\Exception $e) {
                $drafts->others_status = (object)[];
            }
            try {
                $drafts->others_reason = json_decode( $drafts->others_reason );
            } catch (\Exception $e) {
                $drafts->others_reason = (object)[];
            }

            if(!$drafts->others) $drafts->others = array();
            if(!$drafts->others_status) $drafts->others_status = array();
            if(!$drafts->others_reason) $drafts->others_reason = array();
        }

        return response()->json($drafts);
    }

    public function put (Request $request, $id ) {
        $order = \App\Order::find($id);
        $draft_documents = new \App\DraftDocuments();

        if ($order->draft_documents()->first()) {
            $draft_documents = $order->draft_documents()->first();
        }

        // $request = $request->input();
        unset($request['others']);

        $files = array_map( array($this, 'mapFileListToDraft'), $request->all() );

        if ($request->has('others')) {
            $files_others = array_map(array($this, 'mapFileListToDraft'), $request->input('others'));
            $files_others = array_filter($files_others, array($this, 'filterRemoveEmptyImages'));
            $files_others = array_values($files_others);
        } else {
            $files_others = [];
        }

        $draft_documents->draft_bl_status               = $this->getStatus ($draft_documents->draft_bl,               $files, 'draft_bl',               $draft_documents->draft_bl_status);
        $draft_documents->draft_comercial_status        = $this->getStatus ($draft_documents->draft_comercial,        $files, 'draft_comercial',        $draft_documents->draft_comercial_status);
        $draft_documents->packing_list_status           = $this->getStatus ($draft_documents->packing_list,           $files, 'packing_list',           $draft_documents->packing_list_status);
        $draft_documents->certificate_origin_status     = $this->getStatus ($draft_documents->certificate_origin,     $files, 'certificate_origin',     $draft_documents->certificate_origin_status);
        $draft_documents->certificate_fumigation_status = $this->getStatus ($draft_documents->certificate_fumigation, $files, 'certificate_fumigation', $draft_documents->certificate_fumigation_status);
        $draft_documents->certificate_quality_status    = $this->getStatus ($draft_documents->certificate_quality,    $files, 'certificate_quality',    $draft_documents->certificate_quality_status);
        $draft_documents->certificate_weight_status     = $this->getStatus ($draft_documents->certificate_weight,     $files, 'certificate_weight',     $draft_documents->certificate_weight_status);
        $draft_documents->certificate_seguro_status     = $this->getStatus ($draft_documents->certificate_seguro,     $files, 'certificate_seguro',     $draft_documents->certificate_seguro_status);
        $draft_documents->certificate_phyto_status      = $this->getStatus ($draft_documents->certificate_phyto,      $files, 'certificate_phyto',      $draft_documents->certificate_phyto_status);
        $draft_documents->report_status                 = $this->getStatus ($draft_documents->report,                 $files, 'report',                 $draft_documents->report_status);
        $draft_documents->health_certificate_status     = $this->getStatus ($draft_documents->health_certificate,     $files, 'health_certificate',     $draft_documents->health_certificate_status);
        $draft_documents->non_gmo_certificate_status    = $this->getStatus ($draft_documents->non_gmo_certificate,     $files, 'non_gmo_certificate',     $draft_documents->non_gmo_certificate_status);

        unset($files['draft_bl_status']);
        unset($files['draft_comercial_status']);
        unset($files['packing_list_status']);
        unset($files['certificate_origin_status']);
        unset($files['certificate_fumigation_status']);
        unset($files['certificate_quality_status']);
        unset($files['certificate_weight_status']);
        unset($files['certificate_seguro_status']);
        unset($files['certificate_phyto_status']);
        unset($files['report_status']);
        unset($files['health_certificate_status']);
        unset($files['non_gmo_certificate_status']);

        $draft_documents->fill($files);
        $draft_documents->others = json_encode($files_others);
        $draft_documents->order_id = $id;

        // var_dump($draft_documents); exit;

        $status = array();
        $reason = array();

        foreach ($files_others as $oth) {
            $status[] = 1;
            $reason[] = null;
        }

        $draft_documents->others_status = json_encode($status);
        $draft_documents->others_reason = json_encode($reason);

        $draft_documents->allow_access = json_encode($request->input('allowAccess'));
        $draft_documents->required = json_encode($request->input('required'));

        $order->draft_documents()->save($draft_documents);

        if($request->has('sending_emails') && in_array('draft_documents',$request->input('sending_emails'))){
            $this->dispatch(new OrderDraftDocumentsJob($order));
        }

        return response()->json(['success' => 'Draft documents has saved!']);
    }

    public function statusChange ( \Request $request ) {
        $draft = \App\DraftDocuments::find(\Request::input('id'));
        $request = \Request::input();
        unset($request['others']);
        unset($request['others_status']);
        unset($request['others_reason']);

        $request_others_status = \Request::input('others_status');
        $request_others_reason = \Request::input('others_reason');

        $request['others_status'] = json_encode($request_others_status);
        $request['others_reason'] = json_encode($request_others_reason);

        $draft->fill($request);
        $draft->save();

        $draft2 = \App\DraftDocuments::find(\Request::input('id'));
        

        return response()->json(['success' => 'Draft document status has changed!']);
    }
    public function mapFileListToDraft ($e) {
        if ( ! is_array($e) ) return $e;
        if ( ! array_key_exists('url', $e) ) return $e;
        return $e['url'];
    }
    public function filterRemoveEmptyImages ($e) {
        return !!$e;
    }

    public function getStatus ($old_file, $file_list, $new_file_index, $old_status) {
        // Se é o primeiro arquivo retorna o status "Waitting for approval"
        if (!$old_file) return 1;

        // Se o arquivo não foi passado muda o status para 1
        if (!array_key_exists($new_file_index, $file_list)) return 1;

        $new_file = $file_list[$new_file_index];

        // Se é um novo arquivo retorna o status "Waiting for approval"
        if ($old_file != $new_file) return 1;

        // Se o novo arquivo é igual ao antigo ele mantem o status
        if ($old_file == $new_file) return $old_status;

        return 1;
    }
}
