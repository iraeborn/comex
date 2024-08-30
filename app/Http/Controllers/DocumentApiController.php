<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentApiController extends Controller
{
    public function put ( Request $request ) {
        $contract_signed        = $request->input('contract_signed');
        $proforma_signed        = $request->input('proforma_signed');
        $swift_advance          = $request->input('swift_advance');
        $instructions_documents = $request->input('instructions_documents');
        $document_label_model   = $request->input('label_model');

        $contract = null;
        $proforma = null;
        $swift = null;
        $instructions = null;
        $label_model = null;

        if (isset($contract_signed['id']) && !is_null($contract_signed['id'])) {
            $contract = Document::find($contract_signed['id']);
            $contract->update([
                'document_status_id' => $contract_signed['document_status_id'],
                'reason' => $contract_signed['reason'],
            ]);
        }

        if (isset($proforma_signed['id']) && !is_null($proforma_signed['id'])) {
            $proforma = Document::find($proforma_signed['id']);
            $proforma->update([
                'document_status_id' => $proforma_signed['document_status_id'],
                'reason' => $proforma_signed['reason'],
            ]);
        }

        if (isset($swift_advance['id']) && !is_null($swift_advance['id'])) {
            $swift = Document::find($swift_advance['id']);
            $swift->update([
                'document_status_id' => $swift_advance['document_status_id'],
                'reason' => $swift_advance['reason'],
            ]);
        }

        if (isset($instructions_documents['id']) && !is_null($instructions_documents['id'])) {
            $instructions = Document::find($instructions_documents['id']);
            $instructions->update([
                'document_status_id' => $instructions_documents['document_status_id'],
                'reason' => $instructions_documents['reason'],
            ]);
        }

        if (isset($document_label_model['id']) && !is_null($document_label_model['id'])) {
            $label_model = Document::find($document_label_model['id']);
            $label_model->update([
                'document_status_id' => $document_label_model['document_status_id'],
                'reason' => $document_label_model['reason'],
            ]);
        }

        $order = $contract->order()->first();
        $order->order_status_id = 2;

        if($contract_signed['document_status_id'] == 3 && $proforma_signed['document_status_id'] == 3) {
            $order->order_status_id = 3;
        }

        $order->save();
        return response()->json([
            'success' => 'Document status changed successfully.',
            'order_id' => $order->id,
            'status' => $order->order_status()->first()
        ]);
    }
}
