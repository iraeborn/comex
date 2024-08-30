<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewOrderRequests extends FormRequest
{
    public function rules() : array
        {
            return [
                'user_id'   => 'nullable|integer',
                'importer_id' => 'required|integer',
                'exporter_id' => 'required|integer',
                'swift_advance' => 'required',
                'items' => 'required|array',
                'items.*.value' => 'required',
                'items.*.description' => 'required',
                'items.*.botanical_name' => 'required',
                'items.*.crop_year' => 'required',
                'items.*.quantity' => 'required',
                'items.*.total_price' => 'required',
                'items.*.unit_price' => 'required',
                'items.*.advance_payment' => 'required',
                'items.*.container' => 'required',
                'items.*.total_bags' => 'required',
                'items.*.net_weight' => 'required',
                'items.*.gross_weight' => 'required',
                'items.*.hs_code' => 'required',
                'number' => 'required|string',
                'full_packing' => 'required|string',
                'label' => 'required',
                'port_origin' => 'required|string',
                'port_destiny' => 'required|string',
                'payment_conditions' => 'required|string',
                'banks_id' => 'required|integer',
                'specifications' => 'required|array',
                'document' => 'required|array',
                'notify_id' => 'required|integer',
                'consignee_id' => 'required|integer',
                'transportion' => 'required|string',
                'incoterm' => 'required|string',
                'shipment' => 'required|string',
                'signature_id' => 'required|integer',
                'container_type' => 'required|integer',
                'broker_id'   => 'nullable|integer',
                'fumigation_id' => 'nullable',
                'quality_id' => 'nullable|string',
                'seguro_id' => 'nullable|string',
                'weight_id' => 'nullable',
                'inspection_id' => 'nullable',
                'fumigation_agency_id'   => 'nullable|string',
                'insurance_agency_id'  => 'nullable|string',
                'inspection_agency_id'  => 'nullable|string',
                'forwarding_agent_id'  => 'nullable',
                'terminal_agent_id'  => 'nullable',
                'railway_agent_id'  => 'nullable',
                'invoce_advance_status'  => 'nullable|string',
                'invoce_advance_value'   => 'nullable|integer',
                'invoce_total' => 'nullable|integer',
                'invoce_balance' => 'nullable|integer',
                'hs_code' => 'nullable|numeric',
                'unit_comission' => 'nullable|string',
                'orders_document_order' => 'nullable|array',
                'provider_contracts' => 'nullable|array',
            ];
    
        }
}