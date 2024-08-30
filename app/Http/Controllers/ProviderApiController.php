<?php

namespace App\Http\Controllers;

use App\Actions\Provider\LinkProviderContractsAction;
use App\ContractProvision;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProviderApiController extends Controller
{
    protected $linkProviderContractsAction;
    
    public function __construct(LinkProviderContractsAction $linkProviderContractsAction)
    {
        $this->linkProviderContractsAction = $linkProviderContractsAction;
    }

    public function linkOrdersProvider(Request $request)
    {
        $orderIds = $request->input('orders_ids', []);

        foreach ($orderIds as $orderId) {
            ($this->linkProviderContractsAction)($orderId, $request->except('orders_ids'));
        }

        return response()->json(['message' => 'provider is successfully linked.'], Response::HTTP_OK);
    }

    public function unlinkOrderProvider(Request $request)
    {

        $contractProvision = ContractProvision::where('provider_contract_id', $request->contract_id)
        ->where('order_id', $request->order_id)
        ->first();

        if ($contractProvision) {
            $contractProvision->delete();

            return response()->json(['message' => "Order " . $request->order_number ." unlink successfully."], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Erro to unlink order.'], Response::HTTP_NOT_FOUND);
    }
}