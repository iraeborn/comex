<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ProvisionPosting;
use App\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Request;
use stdClass;

class ProvisionPostingsController extends Controller
{

    protected $model;
    protected $user;

    public function __construct(ProvisionPosting $model, Request $request){
        $this->model = $model;
    }

    public function show (Request $request, int $id)
    {
        return $this->model
            ->get();
    }

    public function invoicesReport ()
    {

        $user = User::getUserLogged();

        $rawRecords = $this->model
        ->with([
            'contract_provision.provider_contract.provider',
            'contract_provision.order.shipping',
            'contract_provision.order.mapa'
        ])
        ->whereHas('contract_provision',function($q) use($user){
            $q 
            ->whereHas('order',function($q) use($user){
                $q
                ->where(function($q) use($user){
                    $q 
                    ->whereRaw('? = 1',$user->group->id)
                    ->orWhere('group_id',$user->group->id)
                    ->orWhere('user_id',$user->id);
        
                });
            });
        })
        ->get();

        $records = $rawRecords->map(function ($posting) {

            $supplier = '';
            try {
                $supplier = $posting
                    ->contract_provision
                    ->provider_contract
                    ->provider
                    ->name;
            } catch (Exception $exception) {
                Log::error($exception);
                $supplier = '';
            }

            $booking = '';
            try {
                $booking = $posting->contract_provision->order->shipping->first()->booking;
            } catch (Exception $exception) {
                Log::error($exception);
                $booking = '';
            }

            $nfe = '';
            try {
                $nfe = $posting->contract_provision->order->mapa->nfe_key;
            } catch (Exception $exception) {
                Log::error($exception);
                $nfe = '';
            }

            $po = '';
            try {
                $po = $posting->contract_provision->order->number;
            } catch (Exception $exception) {
                Log::error($exception);
                $po = '';
            }

            $invoiceAmountReal = 0;
            $invoiceAmountDollar = 0;
            if ($posting->currency_type == 'BRL') {
                $invoiceAmountReal = $posting->invoice_amount;
            } else {
                $invoiceAmountDollar = $posting->invoice_amount;
            }
            $record = new stdClass();
            $record->invoice_id = $posting->invoice_id;
            $record->invoice_amount_real = $invoiceAmountReal;
            $record->invoice_amount_dollar = $invoiceAmountDollar;
            $record->invoice_amount_converted = $posting->invoice_amount_converted;
            $record->invoice_currency_fee = $posting->currency_fee;
            $record->invoice_currency_type = $posting->currency_type;
            $record->invoice_due_date = $posting->due_date;
            $record->order_po = $po;
            $record->order_booking = $booking;
            $record->order_nfe = $nfe;
            $record->supplier_name = $supplier;

            return $record;
        });

        return $records;

    }

}
