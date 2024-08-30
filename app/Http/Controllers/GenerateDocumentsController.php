<?php

namespace App\Http\Controllers;

use App\Signatures;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\DocumentOrder;
use App\Specification;
use App\Bank;
use App\Document;
use App\Http\Libs\Pdf;
use App\Order;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Svg\Surface\SurfacePDFLib;

class GenerateDocumentsController
{
    /**
     * @var Signatures
     */
    private $signatures;
    /**
     * @var \false[][]
     */
    private $arrContextOptions;
    protected $domPDF;
    protected $pdf;
    /**
     * @param Signatures $signatures
     */
    public function __construct(Signatures $signatures, DomPDFPDF $domPDF, Pdf $pdf)
    {
        $this->signatures = $signatures;
        $this->domPDF = $domPDF;
        $this->pdf = $pdf;

        $this->arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
    }

    public function contract($orderData)
    {

        if ($signature =  $this->signatures->find($orderData['signature_id'])) {
            $orderData['signature']['text'] = $signature->text;
            $orderData['signature']['term1'] = $signature->term1;
            $orderData['signature']['term2'] = $signature->term2;
            $orderData['signature']['content'] = base64_encode(file_get_contents($signature->url, false, stream_context_create($this->arrContextOptions)));
        }

        $exporter_id = $orderData['exporter_id'];

        if (isset($exporter_id)) {
            $exporter =  User::with('contacts')->find($exporter_id);

            $orderData['exporter'] = $exporter;

            if ($exporter->profile_picture) {
                $orderData['exporter']['profile_picture'] = base64_encode(file_get_contents($exporter->profile_picture, false, stream_context_create($this->arrContextOptions)));
            }
        }

        $orderData['qtdTotalItem'] = 0;
        foreach ($orderData['items'] as $item) {
            $orderData['qtdTotalItem'] += $item['quantity'];
        }

        $orderData['qtdTotalContainers'] = 0;
        foreach ($orderData['items'] as $item) {
            $orderData['qtdTotalContainers'] += $item['container'];
        }

        switch ($orderData['container_type']) {
            case 0:
                $orderData['container_type'] = "Dry 20";
                break;
            case 1:
                $orderData['container_type'] = "Dry 40";
                break;
            case 2:
                $orderData['container_type'] = "Dry 40 High";
                break;
        }

        $nameFile = md5(rand()) . '.pdf';

        $orderData['buyer'] = User::with('contacts')->find($orderData['owner_id']);
        $orderData['phones'] = array();


        if ($orderData['buyer']) {
            $phonesList = [];

            foreach ($orderData['buyer']->contacts as $contact) {
                if ($contact['contact_type_id'] === 3 || $contact['contact_type_id'] === 4) {
                    $phonesList[] = $contact['value'];
                }
            }

            $orderData['phones'] = $phonesList;
        }

        $documentList = [];

        if (is_object($orderData) && method_exists($orderData, 'orders_document_order')) {
            $documentOrders = $orderData->orders_document_order();

            if ($documentOrders->exists()) {
                $documentOrders->each(function ($documentOrder) use (&$documentList) {
                    $documentList[] = $documentOrder->document_order->description;
                }); 
            }
        } elseif (isset($orderData['document'])) {
            foreach ($orderData['document'] as $documentOrder) {
                $document = DocumentOrder::find($documentOrder);

                if ($document) {
                    $documentList[] = $document->description;
                }
            }
        }

        $orderData['documentName'] = $documentList;

        // if (isset($orderData['document'])) {
        //     $documentList = [];
        //     $orderDocument = collect($orderData['document']);
        //     $orderDocument->each(function ($documentOrder) use (&$documentList){
        //         $documentList[] = DocumentOrder::find($documentOrder)->description;
        //     });

        //     $orderData['documentName'] = $documentList;
        // }

        if ($orderData['specifications']) {
            $specificationsList = [];

            foreach ($orderData['specifications'] as $specification) {
                $specificationData = Specification::find($specification)->first();

                if ($specificationData) {
                    $specificationsList[] = $specificationData->description;
                }
            }

            $orderData['specificationsLst'] = $specificationsList;
        }
        $data = $orderData;
        $pdf = $this->domPDF->loadView('documents.contract', compact('data'))->setOptions(['isRemoteEnabled' => true]);

        Storage::put('contract/' . $nameFile, $pdf->output());
        return url('uploads/contract/' . $nameFile);
    }

    public function proforma($orderData)
    {

        if ($signature =  $this->signatures->find($orderData['signature_id'])) {
            $orderData['signature']['text'] = $signature->text;
            $orderData['signature']['term1'] = $signature->term1;
            $orderData['signature']['term2'] = $signature->term2;
            $orderData['signature']['content'] = base64_encode(file_get_contents($signature->url, false, stream_context_create($this->arrContextOptions)));
        }

        if ($exporter =  User::with('contacts')->find($orderData['exporter_id'])) {
            $orderData['exporter'] = $exporter;
            if ($exporter->profile_picture) {
                $orderData['exporter']['profile_picture'] = base64_encode(file_get_contents($exporter->profile_picture, false, stream_context_create($this->arrContextOptions)));
            }
        }

        $orderData['totalPriceItems'] = 0;
        $orderData['buyer'] = User::with('contacts')->find($orderData['owner_id']);
        $orderData['phones'] = array();

        $phones = [];

        foreach ($orderData['buyer']->contacts as $contact) {
            if ($contact['contact_type_id'] === 3 || $contact['contact_type_id'] === 4) {
                $phones[] =  $contact['value'];
            }
        }

        $orderData['phones'] = $phones;

        $orderData['totalAdvancePayment'] = 0;
        $orderData['notify'] = User::with('contacts')->find($orderData['notify_id']);
        $orderData['consignee'] = User::with('contacts')->find($orderData['consignee_id']);

        foreach ($orderData['items'] as $item) {
            $orderData['totalPriceItems'] += $item['total_price'];
            $orderData['totalAdvancePayment'] += $item['advance_payment'];
        }

        $nameFile = md5(rand()) . '.pdf';

        $orderData['bank'] = Bank::find($orderData['banks_id']);

        $specificationsList = [];
        foreach ($orderData['specifications'] as $specificationId) {
            $specification = Specification::find($specificationId)->first();

            if ($specification) {
                $specificationsList[] = $specification->description;
            }
        }
        $orderData['specificationsLst'] = $specificationsList;

        $orderData['qtdTotalContainers'] = 0;
        foreach ($orderData['items'] as $item) {
            $orderData['qtdTotalContainers'] += $item['container'];
        }

        switch ($orderData['container_type']) {
            case 0:
                $orderData['container_type'] = "Dry 20";
                break;
            case 1:
                $orderData['container_type'] = "Dry 40";
                break;
            case 2:
                $orderData['container_type'] = "Dry 40 High";
                break;
        }

        // dd($orderData['exporter']);
        $proformaFileWithPath = $this->pdf->generatePdf(
            'documents.proforma',
            'proforma',
            $orderData,
            'proforma_'.$orderData['number']
        );

        return url($proformaFileWithPath);

        // $pdf = $this->domPDF->loadView('documents.proforma', compact('orderData'))->setOptions(['isRemoteEnabled' => true]);
        // Storage::put('proforma/' . $nameFile, $pdf->output());
        // return url('uploads/proforma/' . $nameFile);
    }

    public function order($data, $prefixNameFile)
    {
        $html = view('documents.order', compact('data'))->render();
        $html = preg_replace('/>\s+</', '><', $html);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $nameFile = $prefixNameFile . '.pdf';

        Storage::put('order/' . $nameFile, $pdf->output());

        $fileUrl = url('uploads/order/' . $nameFile);
        $pdf->stream();
        return $fileUrl;

    }
    
    public function shipping($data, $prefixNameFile)
    {
        $html = view('documents.shipping', compact('data'))->render();
        $html = preg_replace('/>\s+</', '><', $html);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $nameFile = $prefixNameFile . '.pdf';

        Storage::put('shipping/' . $nameFile, $pdf->output());

        $fileUrl = url('uploads/shipping/' . $nameFile);
        $pdf->stream();
        return $fileUrl;
    }

    public function invoice($data, $prefixNameFile)
    {
        $html = view('documents.invoices', compact('data'))->render();
        $html = preg_replace('/>\s+</', '><', $html);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHtml($html);
        $nameFile = $prefixNameFile . '.pdf';

        Storage::put('shipping/' . $nameFile, $pdf->output());

        $fileUrl = url('uploads/shipping/' . $nameFile);
        $pdf->stream();
        return $fileUrl;
    }

    public function draftBill($id)
    {
        
        $data = Order::with([
            'consignee',
            'container_stuffing',
            'items',
            'loadings',
            'mapa',
            'owner',
            'shipping',
            'bills'
        ])->find($id);

        $nameFile = 'draft_bill_'. $data->number .'.pdf';
            
        $html = view('documents.stuffing_loading.draft_bill', compact('data'))->render();
        $html = preg_replace('/>\s+</', '><', $html);
            
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadHtml($html);

        Storage::put('draft_bill/'. $nameFile, $pdf->output());
        $fileUrl = Storage::url('draft_bill/' . $nameFile);
        return response()->json(['message' => 'Arquivo gerado com sucesso!', 'filename' => $nameFile]);
    }
}
