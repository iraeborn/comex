<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\OrderInspectionAgencyJob;

class InspectionAgencyApiController extends Controller
{
    const QUALITY =  6;
    const WEIGHT  =  7;
    const REPORT  = 10;
    const HEALTH  = 12;
    const NONGMO  = 13;

    public function index ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $inspection = $order->inspection_agency()->first();

        if (!$inspection) {
            $inspection = new \App\Inspection();
            $inspection->order_id = $order_id;
            $inspection->inspection_agency_id = 1;
            $inspection->presence_confirmation = 0;
            $inspection->save();
        }

        $inspection->name = "Agency is not defined";
        if(\App\User::find($order->inspection_id))
            $inspection->name = \App\User::find($order->inspection_id)->name;

        $inspection->quality_certificate = null;
        $inspection->weight_certificate = null;
        $inspection->report = null;
        $inspection->health_certificate_original = null;

        if ($original = $order->original_documents()->where('original_documents_type_id', self::QUALITY)->first()) {
            $inspection->quality_certificate_original = $original->url;
        }

        if ($original = $order->original_documents()->where('original_documents_type_id', self::WEIGHT)->first()) {
            $inspection->weight_certificate_original = $original->url;
        }

        if ($original = $order->original_documents()->where('original_documents_type_id', self::REPORT)->first()) {
            $inspection->report_original = $original->url;
        }

        if ($original = $order->original_documents()->where('original_documents_type_id', self::HEALTH)->first()) {
            $inspection->health_certificate_original = $original->url;
        }

        if ($original = $order->original_documents()->where('original_documents_type_id', self::NONGMO)->first()) {
            $inspection->non_gmo_certificate_original = $original->url;
        }

        $draft = $order->draft_documents()->first();

        if ($draft) {
            $inspection->quality_certificate_draft = $draft->certificate_quality;
            $inspection->weight_certificate_draft  = $draft->certificate_weight;
            $inspection->report_draft              = $draft->report;
            $inspection->health_certificate_draft  = $draft->health_certificate;
            $inspection->non_gmo_certificate_draft = $draft->non_gmo_certificate;
        }

        if (!$draft) {
            $inspection->quality_certificate_draft = null;
            $inspection->weight_certificate_draft  = null;
            $inspection->report_draft              = null;
            $inspection->health_certificate_draft  = null;
            $inspection->non_gmo_certificate_draft = null;
        }

        return response()->json($inspection);
    }

    public function post ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $inspection = $order->inspection_agency()->first();
        if (!$inspection) $inspection = new \App\Inspection();

        $inspection->order_id = $order_id;

        $inspection->inspection_start_datetime = \Request::input('inspection_start_datetime');
        $inspection->inspection_end_datetime = \Request::input('inspection_end_datetime');

        $inspection->inspection_agency_id = $order->weight_id;
        $inspection->presence_confirmation = 0;

        $inspection->Save();


        return response()->json(['success' => true]);
    }

    public function addObservation ( \Request $request, $inspection_id ) {
        $observation = new \App\InspectionObservation();
        $user = \App\User::find(\Request::get('user_id'));

        $observation->text    = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->inspection_id = $inspection_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }

    public function getObservation ( \Request $request, $inpecttion_id ) {
        $inspection = \App\Inspection::find($inpecttion_id);

        $observations = $inspection->observations()->get();
        foreach ($observations as &$observation) {
            $user = \App\User::find($observation->user_id);
            $observation->name = $user->name;
            $observation->me = $observation->user_id == \Request::get('user_id');
            $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;
        }

        return response()->json($observations);
    }

    public function putProfile ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $inspection = $order->inspection_agency()->first();

        $draft = \App\DraftDocuments::where('order_id', $order_id)->first();

        $values = \Request::input();

        $inspection->inspection_start_datetime = \Request::input('inspection_start_datetime');
        $inspection->inspection_end_datetime = \Request::input('inspection_end_datetime');
        $inspection->save();

        $draft->certificate_weight = \Request::input('weight_certificate_draft');
        $draft->certificate_quality = \Request::input('quality_certificate_draft');
        $draft->report = \Request::input('report_draft');
        $draft->health_certificate = \Request::input('health_certificate_draft');
        $draft->non_gmo_certificate = \Request::input('non_gmo_certificate_draft');

        $draft->save();

        $weight_certificate_original  = $order->original_documents()->where('original_documents_type_id', self::HEALTH)->first();
        if (!$weight_certificate_original) {
            $weight_certificate_original = new \App\OriginalDocument();
            $weight_certificate_original->order_id = $order_id;
            $weight_certificate_original->original_documents_type_id = self::WEIGHT;
            $weight_certificate_original->original_documents_status_id = 2;
        }

        $quality_certificate_original = $order->original_documents()->where('original_documents_type_id', self::WEIGHT)->first();
        if (!$quality_certificate_original) {
            $quality_certificate_original = new \App\OriginalDocument();
            $quality_certificate_original->order_id = $order_id;
            $quality_certificate_original->original_documents_type_id = self::QUALITY;
            $quality_certificate_original->original_documents_status_id = 2;
        }

        $report_original = $order->original_documents()->where('original_documents_type_id', self::REPORT)->first();
        if (!$report_original) {
            $report_original = new \App\OriginalDocument();
            $report_original->order_id = $order_id;
            $report_original->original_documents_type_id = self::REPORT;
            $report_original->original_documents_status_id = 2;
        }

        $health_certificate_original  = $order->original_documents()->where('original_documents_type_id', self::HEALTH)->first();
        if (!$health_certificate_original) {
            $health_certificate_original = new \App\OriginalDocument();
            $health_certificate_original->order_id = $order_id;
            $health_certificate_original->original_documents_type_id = self::HEALTH;
            $health_certificate_original->original_documents_status_id = 2;
        }

        $non_gmo_certificate_original  = $order->original_documents()->where('original_documents_type_id', self::NONGMO)->first();
        if (!$non_gmo_certificate_original) {
            $non_gmo_certificate_original = new \App\OriginalDocument();
            $non_gmo_certificate_original->order_id = $order_id;
            $non_gmo_certificate_original->original_documents_type_id = self::NONGMO;
            $non_gmo_certificate_original->original_documents_status_id = 2;
        }


        
        $weight_certificate_original->url  = \Request::input('weight_certificate_original');
        $quality_certificate_original->url = \Request::input('quality_certificate_original');
        $report_original->url              = \Request::input('report_original');
        $health_certificate_original->url  = \Request::input('health_certificate_original');
        $non_gmo_certificate_original->url  = \Request::input('non_gmo_certificate_original');

        $weight_certificate_original->save();
        $quality_certificate_original->save();
        $report_original->save();
        $health_certificate_original->save();
        $non_gmo_certificate_original->save();

        return response()->json(['success' => true]);
    }

}
