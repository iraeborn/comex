<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MapaApiController extends Controller
{
    public function index ( \Request $request, $order_id ) {
        $mapa = \App\Mapa::where('order_id', $order_id)->first();
        $order = \App\Order::find($order_id);
        if (!$mapa) {
            $mapa = new \App\Mapa();
            $mapa->order_id = $order_id;
            $mapa->save();
        }

        if ($order) {
            if (($draft = $order->draft_documents()->first())) {
                $mapa->phyto_certificate = $draft->certificate_phyto;
            }

            if (($original = $order->original_documents()->where('original_documents_type_id', 9)->first())) {
                $mapa->phyto_certificate_signed = $original->url;
            }

        }
        
        return response()->json($mapa);
    }

    public function post ( \Request $request, $order_id ) {
        $mapa = \App\Mapa::where('order_id', $order_id)->first();

        if(\Request::input('inspection_date')) {
            $mapa->inspection_date      = \Request::input('inspection_date');
        }

        if(\Request::input('mapa_document')) {
            $mapa->mapa_document        = \Request::input('mapa_document');
        }

        if(\Request::input('mapa_document_signed')) {
            $mapa->mapa_document_signed = \Request::input('mapa_document_signed');
        }


        if(\Request::input('lpco_document')) {
            $url = \Request::input('lpco_document');
            if (is_array(\Request::input('lpco_document'))) {
                $url = \Request::input('lpco_document')['url'];
            }

            $mapa->lpco_document        = $url;
        }

        if(\Request::input('lpco_document_signed')) {
            $url = \Request::input('lpco_document_signed');
            if (is_array(\Request::input('lpco_document_signed'))) {
                $url = \Request::input('lpco_document_signed')['url'];
            }

            $mapa->lpco_document_signed = $url;
        }


        if(\Request::input('phyto_certificate')) {
            $url = \Request::input('phyto_certificate');
            if (is_array(\Request::input('phyto_certificate'))) {
                $url = \Request::input('phyto_certificate')['url'];
            }

            $mapa->phyto_certificate        = $url;

            $order = \App\Order::findOrFail($order_id);
            if ($order && ($draft = $order->draft_documents()->first())) {
                $draft->certificate_phyto = $url;
            }
        }

        if(\Request::input('phyto_certificate_signed')) {
            $url = \Request::input('phyto_certificate_signed');
            if (is_array(\Request::input('phyto_certificate'))) {
                $url = \Request::input('phyto_certificate')['url'];
            }

            $mapa->phyto_certificate_signed = $url;

            $order = \App\Order::findOrFail($order_id);
            $original =  $order->original_documents()->where('original_documents_type_id', 9)->first();
            if (!$original) {
                $original = new \App\OriginalDocument();
                $original->order_id = $order_id;
                $original->original_documents_type_id = 9;
                $original->original_documents_status_id = 2;
            }

            if ($order) {
                $original->url = $url;
            }
        }

        if(\Request::input('nfe_document')) {
            $url = \Request::input('nfe_document');
            if (is_array(\Request::input('nfe_document'))) {
                $url = \Request::input('nfe_document')['url'];
            }

            $mapa->nfe_document        = $url;
        }

        if (\Request::input('due_document')) {
            $url = \Request::input('due_document');
            if (is_array(\Request::input('due_document'))) {
                $url = \Request::input('due_document')['url'];
            }

            $mapa->due_document = $url;
        }


        if(\Request::input('due_code')) {
            $mapa->due_code   = \Request::input('due_code');
        }

        if(\Request::input('ruc_code')) {
            $mapa->ruc_code   = \Request::input('ruc_code');
        }

        if(\Request::input('access_key')) {
            $mapa->access_key = \Request::input('access_key');
        }

        if(\Request::input('lpco_key')) {
            $mapa->lpco_key   = \Request::input('lpco_key');
        }

        if(\Request::input('nfe_key')) {
            $mapa->nfe_key   = \Request::input('nfe_key');
            $order = \App\Order::findOrFail($order_id);
            if ($order) {
                $order->nf = \Request::input('nfe_key');
                $order->save();
            }
        }

        if(\Request::input('dossie')) {
            $mapa->dossie      = \Request::input('dossie');
        }

        $mapa->bill_of_lading = \Request::input('bill_of_lading', null);
        $mapa->currency_fee = \Request::input('currency_fee', 1);

        if(\Request::input('date_currency_fee')) {
            $mapa->date_currency_fee      = \Request::input('date_currency_fee');
        }

        if(\Request::input('observation')) {
            $mapa->observation   = \Request::input('observation');
        }

        if(\Request::input('additional_declaration')) {
            $mapa->additional_declaration   = \Request::input('additional_declaration');
        }

        if(!\Request::input('inspection_date')
            && !\Request::input('mapa_document')
            && !\Request::input('mapa_document_signed')
            && !\Request::input('lpco_document')
            && !\Request::input('lpco_document_signed')
            && !\Request::input('due_code')
            && !\Request::input('ruc_code')
            && !\Request::input('access_key')
            && !\Request::input('lpco_key')) {
            // Log::error(json_encode([$mapa, $_SERVER, $_GET, $_POST]));
        } else {
            $mapa->save();
        }


        return response()->json(['success' => true]);
    }

    public function putProfile ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $mapa = $order->mapa()->first();

        $draft = \App\DraftDocuments::where('order_id', $order_id)->first();
        $original = \App\OriginalDocument::where('order_id', $order_id)->where('original_documents_type_id', 9)->first();
        if (!$original) {
            $original = new \App\OriginalDocument();
            $original->order_id = $order_id;
            $original->original_documents_type_id = 9;
            $original->original_documents_status_id = 2;
        }

        $url = \Request::input('phyto_certificate');
        if (is_array(\Request::input('phyto_certificate'))) {
            $url = \Request::input('phyto_certificate')['url'];
        }

        if ($url) {
            $draft->certificate_phyto = $url;
            $draft->save();

            $mapa->phyto_certificate = $url;
            $mapa->save();
        }

        $url = \Request::input('phyto_certificate_signed');
        if (is_array(\Request::input('phyto_certificate'))) {
            $url = \Request::input('phyto_certificate')['url'];
        }

        if ($url) {
            $original->url = $url;
            $original->save();

            $mapa->phyto_certificate_signed = $url;
            $mapa->save();
        }

        $url = \Request::input('lpco_document');
        if (is_array(\Request::input('lpco_document'))) {
            $url = \Request::input('lpco_document')['url'];
        }

        if ($url) {
            $mapa->lpco_document = $url;
            $mapa->save();
        }

        $url = \Request::input('lpco_document_signed');
        if (is_array(\Request::input('lpco_document_signed'))) {
            $url = \Request::input('lpco_document_signed')['url'];
        }

        if ($url) {
            $mapa->lpco_document_signed = $url;
            $mapa->save();
        }

        $url = \Request::input('due_document');
        if (is_array(\Request::input('due_document'))) {
            $url = \Request::input('due_document')['url'];
        }

        if ($url) {
            $mapa->due_document = $url;
            $mapa->save();
        }

        $url = \Request::input('nfe_document');
        if (is_array(\Request::input('nfe_document'))) {
            $url = \Request::input('nfe_document')['url'];
        }

        if ($url) {
            $mapa->nfe_document = $url;
            $mapa->save();
        }

        if(\Request::input('nfe_key')) {
            $mapa->nfe_key   = \Request::input('nfe_key');
            $order = \App\Order::findOrFail($order_id);
            if ($order) {
                $order->nf = \Request::input('nfe_key');
                $order->save();
            }
        }


        return response()->json(['success' => true]);
    }
}
