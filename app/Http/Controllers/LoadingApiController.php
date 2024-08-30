<?php

namespace App\Http\Controllers;

use App\Document;
use App\Driver;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Libs\Pdf;
use App\Loading;
use App\Vehicle;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class LoadingApiController extends Controller
{
    protected $pdf;
    protected $domPDF;
    protected $generateDocumentsController;

    public function __construct(Pdf $pdf, DomPDFPDF $domPDF, GenerateDocumentsController $generateDocumentsController)
    {
        $this->pdf = $pdf;
        $this->domPDF = $domPDF;
        $this->generateDocumentsController = $generateDocumentsController;
    }

    public function index($order_id)
    {
        $order = \App\Order::find($order_id);
        $loading = $order->loadings()->with(['order', 'order.items'])->first();


        if ($loading) {
            $loading->vehicles = $loading->vehicles()->with(['drivers', 'carriers', 'documents'])->get();

            foreach ($loading->vehicles as &$vehicle) {
                $vehicle->bills = \App\Bill::where('truck_id', $vehicle->id)->get();
            }
        }


        return response()->json($loading);
    }

    public function generateDocs(Request $request, $order_id)
    {
        $order = Order::with('shipping')->find($order_id);
        $loading = $order->loadings()->first();
        $vehicle =  Vehicle::find($request->selected_truck_id);

        $plates = collect([$vehicle->plate, $vehicle->plate_two, $vehicle->plate_three, $vehicle->plate_four])
            ->filter()
            ->implode('_');

        if (!$vehicle) {
            $vehicle = new Vehicle();
        }

        $number = $request->loading_number;

        $vehicle->truck_unloading_datetime = $vehicle['truck_unloading_date'] . ' ' . $vehicle['truck_unloading_time'];
        $vehicle->driver_id = $vehicle['driver_id'];
        $vehicle->carrier_id = $vehicle['carrier_id'];
        $vehicle->loading_number = $order->id . '/' . $number;
        $vehicle->loadings_id = $loading->id;
        $vehicle->loading_location_user_id = $vehicle['loading_location_user_id'];

        if (isset($vehicle['bills']) && array_key_exists('bills', $vehicle)) {
            $vehicle->bill_number = count($vehicle['bills']);
        }

        $vehicle->save();

                $document = new Document();

                $orderDocument = $document->where('entity_id', $vehicle->id)->where('entity', 'Order')->first();
                $shippingDocument = $document->where('entity_id', $vehicle->id)->where('entity', 'Shipping')->first();

                if ($orderDocument && $orderDocument->url && file_exists(base_path('public') . parse_url($orderDocument->url)['path'])) {
                    unlink(base_path('public') . parse_url($orderDocument->url)['path']);
                }

                if ($shippingDocument && $shippingDocument->url && file_exists(base_path('public') . parse_url($shippingDocument->url)['path'])) {
                    unlink(base_path('public') . parse_url($shippingDocument->url)['path']);
                }

                if ($shippingDocument) {
                    $document->where('entity_id', $vehicle->id)->where('entity', 'Shipping')->delete();
                }
                if ($orderDocument) {
                    $document->where('entity_id', $vehicle->id)->where('entity', 'Order')->delete();
                }

                if ($vehicle->deleted_at === NULL) {

                    if (empty($order->items) || empty($order->packing)) {
                        return response()->json(['errors' => 'Sorry! You need to complete the order register. Verify the packing and items are defineds.']);
                    }

                    $data = $vehicle;
                    $data['driver'] = Driver::find($vehicle->driver_id);
                    $data['carrier'] = User::with(['contacts', 'banks'])->find($vehicle->carrier_id);
                    $data['loading'] = $loading;
                    $data['order'] = $order;
                    $data['stuffing'] = Order::find($order->id)->container_stuffing()->first();

                    $terminal_agent_id = $data['order']['terminal_agent_id'];

                    $data['order']['terminal_agent_name'] = $terminal_agent_id ? User::with(['contacts', 'banks'])->find($terminal_agent_id)->name : null;

                    $this->saveProvision($vehicle, $order->id);
                }
                     
                $shippingPrefixNameFile = 'shipping_' . $order->number . '_' . $plates . '_' . date("dmY-His");
                $shippingUrl = $this->generateDocumentsController->shipping($data, $shippingPrefixNameFile);

                $orderPrefixNameFile = 'order_' . $order->number . '_' . $plates . '_' . date("dmY-His");
                $orderUrl = $this->generateDocumentsController->order($data, $orderPrefixNameFile);

                $document->insert([
                    [
                        'url' => $shippingUrl,
                        'document_type_id' => 10,
                        'entity' => 'Shipping',
                        'document_status_id' => 3,
                        'entity_id' => $vehicle->id,
                        'created_at' => $data['date'],
                        'updated_at' => $data['date'],
                    ],
                    [
                        'url' => $orderUrl,
                        'document_type_id' => 9,
                        'entity' => 'Order',
                        'document_status_id' => 3,
                        'entity_id' => $vehicle->id,
                        'created_at' => $data['date'],
                        'updated_at' => $data['date'],
                    ]
                ]);
            
    }

    public function post(Request $request, $order_id)
    {

        $validator = Validator::make($request->all(), $this->getRules(0));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $order = Order::with('shipping')->find($order_id);
        $loading = $order->loadings()->first();

        if (!$loading) {
            $loading = new Loading();
            $loading->order_id = $order_id;
        }

        $loading->fill($request->input());
        $loading->save();
        $remove_vehicles = [];

        if ($request->has('remove_vehicles')) {
            $remove_vehicles = $request->remove_vehicles;
        }

        $loading->vehicles()->whereIn('id', $remove_vehicles)->delete();
        $loading->order()->first()->bills()->whereIn('truck_id', $remove_vehicles)->delete();

        if ($request->has('vehicles')) {
            foreach ($request->vehicles as $key => $vehicle) {

                $temp_vehicle = array_key_exists('id', $vehicle) ? Vehicle::find($vehicle['id']) : new Vehicle();

                if (!$temp_vehicle) {
                    $temp_vehicle = new Vehicle();
                }

                $number = $key + 1;
                $temp_vehicle->fill($vehicle);
                $temp_vehicle->truck_unloading_datetime = $vehicle['truck_unloading_date'] . ' ' . $vehicle['truck_unloading_time'];
                $temp_vehicle->driver_id = $vehicle['driver_id'];
                $temp_vehicle->carrier_id = $vehicle['carrier_id'];
                $temp_vehicle->loading_number = $order->id . '/' . $number;
                $temp_vehicle->loadings_id = $loading->id;
                $temp_vehicle->loading_location_user_id = $vehicle['loading_location_user_id'];

                if (isset($vehicle['bills']) && array_key_exists('bills', $vehicle)) {
                    $temp_vehicle->bill_number = count($vehicle['bills']);
                }

                $temp_vehicle->save();

                $document = new Document();

                $orderDocument = $document->where('entity_id', $temp_vehicle->id)->where('entity', 'Order')->first();
                $shippingDocument = $document->where('entity_id', $temp_vehicle->id)->where('entity', 'Shipping')->first();

                if ($orderDocument && $orderDocument->url && file_exists(base_path('public') . parse_url($orderDocument->url)['path'])) {
                    unlink(base_path('public') . parse_url($orderDocument->url)['path']);
                }

                if ($shippingDocument && $shippingDocument->url && file_exists(base_path('public') . parse_url($shippingDocument->url)['path'])) {
                    unlink(base_path('public') . parse_url($shippingDocument->url)['path']);
                }

                if ($shippingDocument) {
                    $document->where('entity_id', $temp_vehicle->id)->where('entity', 'Shipping')->delete();
                }
                if ($orderDocument) {
                    $document->where('entity_id', $temp_vehicle->id)->where('entity', 'Order')->delete();
                }

                if ($temp_vehicle->deleted_at === NULL) {

                    if (empty($order->items) || empty($order->packing)) {
                        return response()->json(['errors' => 'Sorry! You need to complete the order register. Verify the packing and items are defineds.']);
                    }

                    $data = $temp_vehicle;
                    $data['driver'] = Driver::find($temp_vehicle->driver_id);
                    $data['carrier'] = User::with(['contacts', 'banks'])->find($temp_vehicle->carrier_id);
                    $data['loading'] = $loading;
                    $data['order'] = $order;
                    $data['stuffing'] = Order::find($order->id)->container_stuffing()->first();

                    $terminal_agent_id = $data['order']['terminal_agent_id'];

                    $data['order']['terminal_agent_name'] = $terminal_agent_id ? User::with(['contacts', 'banks'])->find($terminal_agent_id)->name : null;

                    $this->saveProvision($temp_vehicle, $order->id);
                }

                if (isset($vehicle['bills'])) {
                    $this->saveBills($vehicle['bills'], $order->id, $temp_vehicle->id);
                }

                $plates = collect([$vehicle['plate'], $vehicle['plate_two'], $vehicle['plate_three'], $vehicle['plate_four']])
                ->filter()
                ->implode('_');

                $shippingPrefixNameFile = 'shipping_' . $order->number . '_' . $plates . '_' . date("dmY-His");
                $shippingUrl = $this->generateDocumentsController->shipping($data, $shippingPrefixNameFile);

                $orderPrefixNameFile = 'order_' . $order->number . '_' . $plates . '_' . date("dmY-His");
                $orderUrl = $this->generateDocumentsController->order($data, $orderPrefixNameFile);

                $document->insert([
                    [
                        'url' => $shippingUrl,
                        'document_type_id' => 10,
                        'entity' => 'Shipping',
                        'document_status_id' => 3,
                        'entity_id' => $vehicle['id'],
                        'created_at' => $data['date'],
                        'updated_at' => $data['date'],
                    ],
                    [
                        'url' => $orderUrl,
                        'document_type_id' => 9,
                        'entity' => 'Order',
                        'document_status_id' => 3,
                        'entity_id' => $vehicle['id'],
                        'created_at' => $data['date'],
                        'updated_at' => $data['date'],
                    ]
                ]);

            }
        }

        return response()->json($loading);
    }

    protected function saveBills($bills, $order_id, $vehicle_id)
    {
        $bills_id = array_filter(
            $bills,
            function ($bill) {
                return array_key_exists('id', $bill);
            }
        );

        $bills_id = array_map(function ($bill) {
            return $bill['id'];
        }, $bills_id);

        //$bills_to_delete = \App\Bill::where('truck_id', $vehicle_id)->whereNotIn('id', $bills_id)->get();
        // $bills_to_delete->delete();

        foreach ($bills as &$bill) {
            $bill_model = new \App\Bill();
            if (array_key_exists('id', $bill)) {
                $bill_model = \App\Bill::find($bill['id']);
            }

            if (!$bill_model)
                continue;

            $bill_model->number = $bill['number'];
            $bill_model->key = $bill['key'];
            $bill_model->weight = $bill['weight'];
            $bill_model->order_id = $order_id;
            $bill_model->truck_id = $vehicle_id;
            $bill_model->bags = $bill['bags'];

            /*if(array_key_exists('damaged', $bill)) {
                $bill_model->damaged          = $bill['damaged'];
            }

            if(array_key_exists('lack', $bill)) {
                $bill_model->lack             = $bill['lack'];
            }

            if(array_key_exists('leftovers', $bill)) {
                $bill_model->leftovers        = $bill['leftovers'];
            }

            if(array_key_exists('physical_balance', $bill)) {
                $bill_model->physical_balance = $bill['physical_balance'];
            }

            if(array_key_exists('total', $bill)) {
                $bill_model->total            = $bill['total'];
            }

            if( array_key_exists('container_id', $bill)) {
                $bill_model->container_id = $bill['container_id'];
            }*/

            $bill_model->save();
        }

        return $bills;
    }

    protected function saveProvision($vehicle, $orderId)
    {
        $provision = \App\ContractProvision::where('order_id', $orderId)
            ->where('truck_id', $vehicle->id)->first();

        if (!$provision) {
            $provision = new \App\ContractProvision();
        }

        if (!$vehicle->provider_contract_id) {
            $provision->delete();
            return false;
        } else {
            $contract = \App\ProviderContract::findOrFail($vehicle->provider_contract_id);

            $provision->order_id = $orderId;
            $provision->truck_id = $vehicle->id;
            $provision->provider_contract_id = $contract->id;
            $provision->status = 1;

            $provision->save();
            return true;
        }
    }

    protected function getRules($id)
    {
        $rule = [
            'start_truck_loading_date' => 'required',
            'end_truck_loading_date' => 'required',
            'loading_location' => 'required',
            'unloading_location' => 'required',
            'transit_time' => 'required',
            'date_ptax' => 'required',
            'tax_ptax' => 'required'
        ];

        $rule = [];

        return $rule;
    }
}