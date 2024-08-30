<?php

namespace App\Actions\Order;

use App\Document;
use App\DocumentStatus;
use App\Http\Controllers\GenerateDocumentsController;
use App\Order;
use App\OriginalDocumentType;

class CreateContractAction
{
    protected $generateDocumentsController;
    public function __construct(GenerateDocumentsController $generateDocumentsController)
    {
        $this->generateDocumentsController = $generateDocumentsController;

    }
    public function __invoke(array $order)
    {
        $orderData = Order::find($order['id'])->load('items');
        $contractUrl = $this->generateDocumentsController->contract($orderData);

        $contract = new Document();
        $documentContract = $contract->where('order_id', $order['id'])
            ->where('document_type_id', 1)
            ->first();

        if ($documentContract && file_exists(base_path('public') . parse_url($documentContract->url)['path']))
            unlink(base_path('public') . parse_url($documentContract->url)['path']);

        $contract->where('order_id', $order['id'])
            ->where('document_type_id', OriginalDocumentType::CONTRACT)
            ->delete();

        $order = Order::find($order['id']);

        $contract->url = $contractUrl;
        $contract->document_type_id = OriginalDocumentType::CONTRACT;
        $contract->document_status_id = DocumentStatus::APROVED;

        $order->documents()->save($contract);
        
        return $contractUrl;
    }
}