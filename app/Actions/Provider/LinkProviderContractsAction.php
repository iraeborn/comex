<?php

namespace App\Actions\Provider;

use App\Actions\Order\CreateContractAction;
use App\Actions\Order\CreateProformaAction;
use App\ContractProvision;
use App\Order;
use App\ProviderContract;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LinkProviderContractsAction
{
    protected $createContractAction;
    protected $createProformaAction;

    public function __construct(CreateContractAction $createContractAction, CreateProformaAction $createProformaAction)
    {
        $this->createContractAction = $createContractAction;
        $this->createProformaAction = $createProformaAction;
    }
    public function __invoke(string $order_id, array $request)
    {
        $provider_type = isset($request['provider_type']) ? $request['provider_type'] : null;
        $provider_id = isset($request['provider_id']) ? $request['provider_id'] : null;
        $contract_id = isset($request['contract_id']) ? $request['contract_id'] : null;

        if(isset($request['provider_type'])){
            $order = Order::find($order_id);
            $order[$provider_type] = $provider_id;
            $order->save();
        }

        if(!$contract_id){
            return;
        }

        $isProviderContractValid = ProviderContract::where('id', $contract_id)
            ->where('provider_id', $provider_id)
            ->exists();

        if (!$isProviderContractValid) {
            throw new UnprocessableEntityHttpException('contract.is.not.related.to.the.provider');
        }
        
        $existingContract = ContractProvision::where('order_id', $order_id)
        ->whereHas('provider_contract', function ($query) use ($provider_id) {
            $query->where('provider_id', $provider_id);
        })
        ->first();

        if ($existingContract) {
            $existingContract->update(['provider_contract_id' => $contract_id]);
        } else {
            ContractProvision::create([
                'provider_contract_id' => $contract_id,
                'order_id' => $order_id,
                'status' => 0,
            ]);
        }
                
    }
}