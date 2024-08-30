<?php

namespace App\Actions\Order;

use App\Actions\Provider\LinkProviderContracts;
use App\Actions\Transactions\NewTransactionAction;
use App\ContractProvision;
use App\Document;
use App\Http\Controllers\GenerateDocumentsController;
use App\Item;
use App\Order;
use App\OrderDocumentOrder;
use App\OrderSpecification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewOrderAction
{
    protected $document;
    protected $orderDocumentOrder;
    protected $user;
    protected $newTransactionAction;
    protected $newOrderAction;

    public function __construct(GenerateDocumentsController $document, OrderDocumentOrder $orderDocumentOrder, NewTransactionAction $newTransactionAction)
    {
        $this->document = $document;
        $this->orderDocumentOrder = $orderDocumentOrder;
        $this->newTransactionAction = $newTransactionAction;
    }

    public function __invoke(array $request)
    {

        $order = new Order();
        $order->owner_id = $request['importer_id'];
        $order->exporter_id = $request['exporter_id'];
        $order->signature_id = $request['signature_id'];
        $order->swift_advance = $request['swift_advance'];
        $order->label = $request['label'];
        $order->user_id = $request['user_id'];
        $order->broker_id = $request['broker_id'];
        $order->number = $request['number'];
        $order->invoce_advance_status = $request['invoce_advance_status'];
        $order->invoce_advance_value = $request['invoce_advance_value'];
        $order->invoce_total = $request['invoce_total'];
        $order->invoce_balance = $request['invoce_balance'];
        $order->packing = $request['full_packing'];
        $order->port_origin = $request['port_origin'];
        $order->port_destiny = $request['port_destiny'];
        $order->payment_conditions = $request['payment_conditions'];
        $order->incoterm = $request['incoterm'];
        $order->banks_id = $request['banks_id'];
        $order->notify_id = $request['notify_id'];
        $order->consignee_id = $request['consignee_id'];
        $order->shipment = $request['shipment'];
        $order->transportion = $request['transportion'];
        $order->unit_comission = $request['unit_comission'] ? $request['unit_comission'] : 0;
        $order->container_type = $request['container_type'];

        $order->save();

        if ($order->exporter) {
            $order->group_id = $order->exporter->group_id;
            $order->save();
        }
        
        foreach ($request['specifications'] as $specification) {
            $ordersSpecifications = new OrderSpecification();
            $ordersSpecifications->orders_id = $order->id;
            $ordersSpecifications->specifications_id = $specification;
            $ordersSpecifications->created_at = Carbon::now();
            $ordersSpecifications->updated_at = Carbon::now();
            $ordersSpecifications->save();
        }
        
        foreach ($request['document'] as $document_order) {
            $documentOrder = new OrderDocumentOrder();
            $documentOrder->orders_id = $order->id;
            $documentOrder->document_order_id = $document_order;
            $documentOrder->created_at = Carbon::now();
            $documentOrder->updated_at = Carbon::now();
            $documentOrder->save();
        }

        $total_itens_value = 0;

        foreach ($request['items'] as $item_data) {
            $item = new Item();
            $item->description = $item_data['description'];
            $item->botanical_name = isset($item_data['botanical_name']) ? $item_data['botanical_name'] : null;
            $item->crop_year = $item_data['crop_year'];
            $item->quantity = $item_data['quantity'];
            $item->unit_price = $item_data['unit_price'];
            $item->total_price = $item_data['total_price'];
            $item->value = $item_data['value'];
            $item->total_bags = $item_data['total_bags'];
            $item->net_weight = $item_data['net_weight'];
            $item->gross_weight = $item_data['gross_weight'];
            $item->advance_payment = $item_data['advance_payment'];
            $item->container = $item_data['container'];
            $item->hs_code = $item_data['hs_code'];
            $order->items()->save($item);
            
            $total_itens_value = $total_itens_value + $item_data['total_price'];
        }
                
        $contractUrl = $this->document->contract(
            $request,
            $order->id
        );

        $contract = new Document();
        $contract->url = $contractUrl;
        $contract->document_type_id = 1;
        $contract->document_status_id = 3;

        $proformaUrl = $this->document->proforma(
            $request,
            $order->id
        );

        $proforma = new Document();
        $proforma->url = $proformaUrl;
        $proforma->document_type_id = 3;
        $proforma->document_status_id = 3;

        $order->documents()->save($contract);
        $order->documents()->save($proforma);

        $initial_balance_value = number_format($total_itens_value, 2, ',', '.');

        $transactionRequest = new Request([
            'balance' => $initial_balance_value,
            'type'  => 1,
            'data' => Carbon::now(),
        ]);

        ($this->newTransactionAction)($transactionRequest, $order->id);
        
        return response()->json(['success' => 'Order is successfully added.']);
    }

    // private function linkProviderContracts(int $orderID, array $contractsData)
    // {
    //     foreach ($contractsData as $contractData) {

    //         ContractProvision::create([
    //             'order_id' => $orderID,
    //             'provider_contract_id' => $contractData['provider_contract_id'],
    //             'status' => 0
    //         ]);
    //     }
    // }
}