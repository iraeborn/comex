<?php

namespace App\Http\Controllers\API;

use App\ContractProvision;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderContractRequest;
use App\ProviderContract;
use App\User;
use Illuminate\Http\Request;

class ProviderContractsController extends Controller
{

    protected $providerContract;

    public function __construct(ProviderContract $providerContract)
    {
        $this->providerContract = $providerContract;
    }

    public function index(Request $request)
    {
        $user = User::getUserLogged();
        $perPage = $request->input('perPage', 25);
        return $this->providerContract->has('provider')
            ->whereHas('provider',function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id)
                    ->orWhere('id', $user->id);
            })
            ->with(['provider', 'contract_services','service'])
            ->paginate($perPage);
    }

    public function store(ProviderContractRequest $request)
    {
        // transform value to store as integer in database
        $servicesData = $request->input('contract_services');
        $transformedData = array_map(function ($element) {
            $element['billing_value'] *= 10000;

            return $element;
        }, $servicesData);

        $provider = ProviderContract::create($request->all());
        $provider->contract_services()->createMany(
            $transformedData
        );

        return response()->json(['success' => 'Provider is successfully created.']);
    }

    public function show(Request $request, int $id)
    {
        $orders = [];
        $providerContract = ProviderContract::with('contract_services')->find($id);

        $ContractProvision = ContractProvision::where('provider_contract_id', $id)->with(['order'])->get();
        $ContractProvision->each(function ($contractProvision)  use (&$orders) {
            $orders[] = $contractProvision->order;
        });

        return response()->json([
            "id" => $providerContract->id,
            "provider_id" => $providerContract->provider_id,
            "service_id" => $providerContract->service_id,
            "identifier" => $providerContract->identifier,
            "service_type" => $providerContract->service_type,
            "service_location" => $providerContract->service_location,
            "negotiated_terms" => $providerContract->negotiated_terms,
            "is_active" => $providerContract->is_active,
            "expirated_at" => $providerContract->expirated_at,
            "created_at" => $providerContract->created_at,
            "updated_at" => $providerContract->updated_at,
            "contract_services" => $providerContract->contract_services,
            "orders" => $orders
        ]);
    }

    public function update(ProviderContractRequest $request, int $id)
    {
        $provider = $this->providerContract->find($id);
        $provider->update($request->all());

        $servicesData = $request->input('contract_services');
        $transformedData = array_map(function ($element) {
            $element['billing_value'] *= 10000;

            return $element;
        }, $servicesData);

        $provider->contract_services()->delete();
        $provider->contract_services()->createMany($transformedData);

        return response()->json(['success' => 'Provider is successfully updated.']);
    }

    public function delete(int $id)
    {
        $providerContract = $this->providerContract->find($id);
        
        if (!$providerContract) {
            return response()->json(['error' => 'Provider not found.'], 404);
        }

        foreach ($providerContract->contractProvision as $provision) {
            if($provision->trashed()){
                throw new \Exception('Provision has already been deleted.');
            }
            foreach ($provision->provision_posting as $posting) {

                if ($posting->trashed()) {
                    throw new \Exception('Provision Posting has already been deleted.');
                }

                if ($posting->foreignAccount && $posting->foreignAccount->trashed()) {
                    throw new \Exception('Foreign Account has already been deleted.');
                }

                if ($posting->foreignAccount) {
                    $posting->foreignAccount->delete();
                }

                $posting->delete();
            }
            $provision->delete();
        }
    
        foreach ($providerContract->contract_services as $service) {
            if ($service->trashed()) {
                throw new \Exception('Contract Service has already been deleted.');
            }
            $service->delete();
        }
    
        if ($providerContract->trashed()) {
            throw new \Exception('Provider Contract has already been deleted.');
        }
        
        $providerContract->delete();
    
        return response()->json(['success' => 'Provider was successfully deleted.']);
    }
    
}
