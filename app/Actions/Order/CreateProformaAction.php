<?php

namespace App\Actions\Order;

use App\Document;
use App\DocumentStatus;
use App\Http\Controllers\GenerateDocumentsController;
use App\Order;
use App\OriginalDocumentType;

class CreateProformaAction
{
    protected $generateDocumentsController;
    public function __construct(GenerateDocumentsController $generateDocumentsController)
    {
        $this->generateDocumentsController = $generateDocumentsController;

    }
    public function __invoke(array $order)
    {
        $orderData = Order::find($order['id'])->load('items');
        $proformaUrl = $this->generateDocumentsController->proforma(
            $orderData,
            $order['id']
        );

        $proforma = new Document();
        $documentProforma = $proforma->where('order_id', $order['id'])
            ->where('document_type_id', 3)
            ->first();

        if ($documentProforma && file_exists(base_path('public') . parse_url($documentProforma->url)['path']))
            unlink(base_path('public') . parse_url($documentProforma->url)['path']);

        $proforma->where('order_id', $order['id'])
            ->where('document_type_id', OriginalDocumentType::PROFORMA_INVOICE)
            ->delete();

        $order = Order::find($order['id']);

        $proforma->url = $proformaUrl;
        $proforma->document_type_id = OriginalDocumentType::PROFORMA_INVOICE;
        $proforma->document_status_id = DocumentStatus::APROVED;

        $order->documents()->save($proforma);
        
        return $proformaUrl;

    }
}