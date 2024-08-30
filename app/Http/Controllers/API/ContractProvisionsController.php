<?php

namespace App\Http\Controllers\API;

use App\ContractProvision;
use App\ForeignAccountPosting;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvisionPostingRequest;
use App\ProvisionPosting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractProvisionsController extends Controller
{

    protected $model;

    public function __construct(ContractProvision $model){
        $this->model = $model;
    }

    public function index (Request $request)
    {

        $user = User::getUserLogged();
        $provider = $request->input('provider');
        $service_type = $request->input('service_type');
        $order_number = $request->input('order_number');
        $booking = $request->input('booking');
        $initial_date = $request->input('initial_date');
        $final_date = $request->input('final_date');
        $status = $request->has('status') ? $request->input('status') : 1;

        $provisions = $this->model
            ->with([
                'provider_contract.provider',
                'provider_contract.service',
                'provider_contract.contract_services',
                'order.items',
                'order.shipping',
                'order.mapa'
                ])
            ->whereHas('order',function($query) use($user){
                $query->where(function($query) use($user){
                    $query->whereRaw('? = 1',$user->group->id)
                    ->orWhere('id',$user->group->id);
                });
            })
            ->when($request->has('filter') && $request->input('filter'), function ($query) use ($provider, $service_type, $order_number, $booking, $initial_date, $final_date) {
                $query->when(isset($provider), function ($query) use ($provider){
                    return $query->whereHas('provider_contract', function ($query) use ($provider) {
                        return $query->where('provider_id', $provider);
                    });
                })->when(isset($service_type), function ($query) use ($service_type) {
                    return $query->whereHas('provider_contract', function ($query) use ($service_type) {
                        return $query->whereHas('service', function($query) use ($service_type) {
                            $query->where('name', 'like', '%' . $service_type . '%');
                        });
                    });
                })->when(isset($order_number), function ($query) use ($order_number){
                    return $query->whereHas('order', function ($query) use ($order_number){
                        return $query->where('number', 'like', '%' . $order_number . '%');
                    });
                })->when(isset($booking), function ($query) use ($booking) {
                    return $query->whereHas('order', function ($query) use ($booking) {
                        return $query->whereHas('shipping', function ($query) use ($booking){
                            return $query->where('booking', 'like', '%' . $booking . '%');
                        });
                    });
                })->when(isset($initial_date) && isset($final_date), function ($query) use ($initial_date, $final_date){
                    return $query->whereBetween('created_at', [$initial_date, $final_date]);
                });
            })
            ->where('status', $status)
            ->get();

        $provisionWithValues = $provisions->map(function ($provision) {


            $provision = $this->calculateProvisionValues($provision);
            return $provision;
        });

        return $provisionWithValues;
    }

    public function show (Request $request, int $id)
    {

        $provision = $this->model
            ->with([
                'provider_contract.provider',
                'provider_contract.contract_services',
                'order.items',
                'order.shipping',
                'order.mapa',
                'provision_posting.foreignAccount'
            ])
            ->has('order')
            ->find($id);

        return $this->calculateProvisionValues($provision);
    }

    private function calculateProvisionValues ($provision)
    {

        $services = $provision->provider_contract->contract_services;

        $invoiceAmount = 0;
        $dolarTotalAmount = 0;
        $realTotalAmount = 0;
        $qtyContainer = 0;
        $booking = '';
        $weight = 1;

        if ($provision->vehicle) {
            $weight = $provision->vehicle->wheight;
        }

        if ($provision->order->shipping->first()) {
            $booking = $provision->order->shipping->first()->booking;
        }

        if ($provision->order->items->count() > 0) {
            $qtyContainer = $provision->order->items->sum(function ($item) {

                if (is_numeric($item->container)) {
                    return $item->container;
                }

                return 0;
            });

            $invoiceAmount = $provision->order->items->sum(function ($item) {
                if (is_numeric($item->total_price)) {
                    return $item->total_price;
                }

                return 0;
            });
        }

        if ($services->count() > 0) {
            foreach ($services as $service) {

                $factor = 1;
                $budgetedAmount = 0;
                if ( ($service->billing_factor_type == 1) or ($service->billing_factor_type == 2) ) {
                    $factor = $qtyContainer;
                } else if ($service->billing_factor_type == 3 && $provision->vehicle) {
                    $factor = $weight / 1000;
                    $qtyContainer = $weight . ' KGS';
                } else if ($service->billing_factor_type == 5) {
                    $factor = ($invoiceAmount * (1 + ($service->billing_value / 100))) / 100;
                }

                $budgetedAmount = $service->billing_value * $factor;

                
                if ($service->currency_type == 'USD') {
                    $dolarTotalAmount += $budgetedAmount;
                } else {
                    $realTotalAmount += $budgetedAmount;
                }
            }
        }

        $statusText = "A FATURAR";
        if ($provision->status == 2) {
            $statusText = "FATURADO";
        }

        $provision->dolar_budgeted_amount = $dolarTotalAmount;
        $provision->real_budgeted_amount = $realTotalAmount;
        $provision->booking = $booking;
        $provision->quantity_container = $qtyContainer;
        $provision->status_text = $statusText;

        if (
            $provision->provider_contract 
            && ($terms = $provision->provider_contract->negotiated_terms)
            && ($days = intval($terms)) > 0
        ) {
            $service = $provision->provider_contract->service_type;
            $date = null;

            if ($terms && (
                substr_count(strtolower($terms), 'deadline da carga')
                || substr_count(strtolower($terms), 'deadline carga')
                || substr_count(strtolower($terms), 'loading deadline')
                || substr_count(strtolower($terms), 'loading')
                || substr_count(strtolower($terms), 'carga')
                || substr_count(strtolower($terms), 'deadline')
            )) {
                if ($provision->order && $provision->order->loadings()->first() && ($loading_dealine = $provision->order->loadings()->first()->loading_deadline)) {
                    $date = $loading_deadline;
                }
            } elseif (
                ($terms && (
                    substr_count(strtolower($terms), 'fumigação') 
                    || substr_count(strtolower($terms), 'fumigacão')
                    || substr_count(strtolower($terms), 'fumigaçao')
                    || substr_count(strtolower($terms), 'fumigacao')
                    || substr_count(strtolower($terms), 'fumigation')
                )) 
                ||
                ($service && (
                    substr_count(strtolower($service), 'fumigação')
                    || substr_count(strtolower($service), 'fumigacão')
                    || substr_count(strtolower($service), 'fumigaçao')
                    || substr_count(strtolower($service), 'fumigacao')
                    || substr_count(strtolower($service), 'fumigation')
                ))       
            ) {
                if ($provision->order && $provision->order->fumigation()->first() && ($fumigation_conclusion = $provision->order->fumigation()->first()->date_of_conclusion)) {
                    $date = $fumigation_conclusion;
                }
            } elseif ($terms && (
                substr_count(strtolower($terms), 'eta')
                || substr_count(strtolower($terms), 'chegada')
                || substr_count(strtolower($terms), 'arrival')
                || substr_count(strtolower($terms), 'desembarque')
            )) {
                if ($provision->order && $provision->order->shipping()->first() && ($eta = $provision->order->shipping()->first()->eta)) {
                    $date = $eta;
                }
            } else {
                if ($provision->order && $provision->order->shipping()->first() && ($etd = $provision->order->shipping()->first()->etd)) {
                    $date = $etd;
                }
            }


            if ($date) {
                $date = new Carbon($date);

                if ($terms && (
                    substr_count(strtolower($terms), 'antes')
                    || substr_count(strtolower($terms), 'before')
                )) {
                    $date->subDays($days);
                } else {
                    $date->addDays($days);
                }

                $provision->due_date = $date->format('Y-m-d');
            } else {
                $provision->due_date = null;
            }
        }

        return $provision;
    }

    public function storePosting (ProvisionPostingRequest $request, int $id)
    {

        $request->merge(['contract_provision_id' => $id]);

        //convert to integer
        $originalInvoiceAmount = $request->get('invoice_amount', 0);
        $invoiceAmount = $request->get('invoice_amount', 0) * 100;
        $request->merge(['invoice_amount' => $invoiceAmount]);

        $fee = $request->get('currency_fee', 1);
        if ($fee < 1) {
            $fee = 1;
        }

        $feeConverted = $fee * 100;
        $request->merge(['currency_fee' => $feeConverted]);

        $amountedConverted = (int) ( ($originalInvoiceAmount * $fee) * 100);
        $request->merge(['invoice_amount_converted' => $amountedConverted]);

        $provision = ContractProvision::find($id);
        $provision->status = 2;
        $provision->save();

        $posting = ProvisionPosting::create($request->all());

        if ($posting && $request->has('foreign_account') && $request->foreign_account) {
            $foreign = ForeignAccountPosting::create([
                'supplier_id' => $request->input('supplier_id'),
                'provision_posting_id' => $posting->id,
                'invoice_id' => $request->input('invoice_id'),
                'description' => $request->input('service_type'),
                'invoice_amount' => $request->input('invoice_amount'),
                'invoice_amount_converted' => $request->input('invoice_amount_converted'),
                'currency_fee' => $request->input('currency_fee'),
                'currency_type' => $request->input('currency_type'),
                'due_date' => $request->input('due_date')
            ]);
        }

        return $posting;
    }

    public function updatePosting (ProvisionPostingRequest $request, int $provisionID, int $postingID) {

        $request->merge(['contract_provision_id' => $provisionID]);

        //convert to integer
        $originalInvoiceAmount = $request->get('invoice_amount', 0);
        $invoiceAmount = $request->get('invoice_amount', 0) * 100;
        $request->merge(['invoice_amount' => $invoiceAmount]);

        $fee = $request->get('currency_fee', 1);
        if ($fee < 1) {
            $fee = 1;
        }

        $feeConverted = $fee * 100;
        $request->merge(['currency_fee' => $feeConverted]);


        $amountedConverted = (int) ( ($originalInvoiceAmount * $fee) * 100);
        $request->merge(['invoice_amount_converted' => $amountedConverted]);

        $provision = ContractProvision::find($provisionID);
        $provision->status = 2;
        $provision->save();

        $posting = ProvisionPosting::where('id', '=', $postingID)
        ->update(
            [
                'invoice_id' => $request->input('invoice_id'),
                'service_type' => $request->input('service_type'),
                'invoice_amount' => $request->input('invoice_amount'),
                'invoice_amount_converted' => $request->input('invoice_amount_converted'),
                'currency_fee' => $request->input('currency_fee'),
                'currency_type' => $request->input('currency_type'),
                'due_date' => $request->input('due_date')
            ]
        );

        $posting = ProvisionPosting::find($postingID);

        if ($posting && $request->has('foreign_account')) {
            $foreign = ForeignAccountPosting::where('provision_posting_id', $posting->id)->first();
            
            if ($request->input('foreign_account')) {
                $data = [
                    'supplier_id' => $request->input('supplier_id'),
                    'provision_posting_id' => $posting->id,
                    'invoice_id' => $request->input('invoice_id'),
                    'description' => $request->input('service_type'),
                    'invoice_amount' => $request->input('invoice_amount'),
                    'invoice_amount_converted' => $request->input('invoice_amount_converted'),
                    'currency_fee' => $request->input('currency_fee'),
                    'currency_type' => $request->input('currency_type'),
                    'due_date' => $request->input('due_date')
                ];

                if ($foreign) {
                    $foreign->update($data);
                } else {
                    $foreign = ForeignAccountPosting::create($data);
                }
            } else if ($foreign) {
                $foreign->delete();
            }
        }

        return ProvisionPosting::with('foreignAccount')->find($postingID);

    }

    public function deletePosting (int $provisionID, int $postingID) {

        $posting = ProvisionPosting::find($postingID);
        $posting->delete();

        $provisionPostings = ProvisionPosting::where('contract_provision_id', '=', $provisionID)->count();
        if ($provisionPostings == 0) {
            $provision = ContractProvision::find($provisionID);
        $provision->status = 1;
        $provision->save();
        }

        return response()->json(['sucess' => true]);
    }

    public function deleteProvision(int $provisionId)
    {
        ContractProvision::findOrFail($provisionId)->delete();

        return response()->json(['success' => 'Provision is successfully deleted.']);
    }
}
