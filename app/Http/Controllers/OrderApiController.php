<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateContractAction;
use App\Actions\Order\CreateProformaAction;
use App\Actions\Order\NewOrderAction;
use App\Actions\Transactions\NewTransactionAction;
use App\Container;
use App\ContractProvision;
use App\Document;
use App\Http\Controllers\GenerateDocumentsController;
use App\Order;
use App\OrderDocumentOrder;
use App\HistoryLog;
use App\OrderSpecification;
use App\Requests\NewOrderRequests;
use App\Service;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
{

    protected $generateDocumentsController;
    protected $orderDocumentOrder;
    protected $user;
    protected $newTransactionAction;
    protected $createContractAction;
    protected $createProformaAction;
    protected $newOrderAction;

    public function __construct(GenerateDocumentsController $generateDocumentsController, OrderDocumentOrder $orderDocumentOrder, NewTransactionAction $newTransactionAction, CreateContractAction $createContractAction, CreateProformaAction $createProformaAction)
    {
        $this->generateDocumentsController = $generateDocumentsController;
        $this->orderDocumentOrder = $orderDocumentOrder;
        $this->newTransactionAction = $newTransactionAction;
        $this->createContractAction = $createContractAction;
        $this->createProformaAction = $createProformaAction;
        $this->user = User::getUserLogged();
    }


    protected $messages = [
        'items.required' => 'At last one item is required',
        'document_contract_signed.required' => 'The document contract signed is required',
        'document_proforma_signed.required' => 'The document proforma signed is required',
        'document_swift_advance.required' => 'The document swift advance is required',
    ];

    public function index(Request $request)
    {

        $user = User::getUserLogged();
        $initial_date = $request->input('initial_date');
        $final_date = $request->input('final_date');
        $date_type = $request->date_type;
        $total_container = 0;

        $services = Service::all();

        $orders = Order::where('group_id', $user->group_id)
            ->with('items')
            ->has('owner')
            
            ->where(function ($q) use ($user) {
                return $q->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })

            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user){
                return $query->where('exporter_id', $user->id);
            })

            ->when($request->input('status') == 'pending', function ($query) use ($services) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                })
                ->with('contract_provisions.provider_contract.service');
            })

            ->when(
                $request->input('status') == 'loading' || $request->input('status') == 'release' || $request->input('status') == 'transit',
                function ($query) {
                    return $query->has('loadings')->whereHas('shipping', function ($query) {
                        return $query->whereNotNull('booking');
                    });
                }
            )

            ->when($request->input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })

            ->when($request->input('status') == 'missing_docs' && !$request->input('filter'), function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')->where('etd', '>=', '2020-01-01');
                });
            })

            ->when($request->has('filters'), function ($query) use ($request){
                if (is_array($request->input('filters'))) {
                    foreach ($request->input('filters') as $filter) {
                        $this->filterOrders($query, $filter);
                    }
                } elseif ($request->input('filters')) {
                    $this->filterOrders($query, $request->input('filters'));
                }
            })

            ->when(isset($initial_date) && isset($final_date), function ($query) use ($initial_date, $final_date, $date_type){
                switch ($date_type) {
                    case 1:
                        return $query->whereBetween('created_at', [$initial_date, $final_date]);
                    
                    case 2:
                        return $query->whereHas('shipping', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('eta', [$initial_date, $final_date]);
                        });
                    
                    case 3:
                        return $query->whereHas('shipping', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('etd', [$initial_date, $final_date]);
                        });
                    case 4:
                        return $query->whereHas('mapa', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('inspection_date', [$initial_date, $final_date]);
                        });
                    case 5:
                        return $query->whereHas('fumigation', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('init', [$initial_date, $final_date]);
                        });
                    case 6:
                        return $query->whereHas('fumigation', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('end', [$initial_date, $final_date]);
                        });
                    case 7:
                        return $query->whereHas('shipping', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('draft_deadline', [$initial_date, $final_date]);
                        });
                    case 8:
                        return $query->whereHas('shipping', function ($query) use ($initial_date, $final_date) {
                            $query->whereBetween('loading_deadline', [$initial_date, $final_date]);
                        });
                }
            })

            ->orderByDesc('id')->get();
        
        
            $orders->transform(function ($order) use ($services) {
                $relatedServiceIds = $order->contract_provisions->pluck('provider_contract.service.id')->toArray();
            
                $order->services_status = $services->map(function ($service) use ($relatedServiceIds) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'ativo' => in_array($service->id, $relatedServiceIds),
                    ];
                });
            
                return $order;
            });

            foreach ($orders as $i => &$order) {
                if($request->input('status') == 'pending'){
                    $order->exporter_name = $order->exporter->name;
                    $order->contract_provisions = $order->contract_provisions()->get()->toArray();

                }
                $order->owner_name = $order->user->name;
                $order->owner = $order->owner_id;
                $order->document_contract_signed = null;
                $order->document_proforma_signed = null;
                $order->document_swift_advance = null;
                $order->document_label_model = null;
                $order->document_instructions_documents = null;

                $contract_signed = $order->documents->where('document_type_id', 2)->first();
                if ($contract_signed) {
                    $order->document_contract_signed = $contract_signed->toArray();
                }

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed) {
                $order->document_proforma_signed = $proforma_signed->toArray();
            }

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance) {
                $order->document_swift_advance = $swift_advance->toArray();
            }

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents) {
                $order->document_instructions_documents = $instructions_documents->toArray();
            }

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model) {
                $order->document_label_model = $label_model->toArray();
            }

            if ($request->input('status') == 'missing_docs') {
                $order->drafts = $order->draft_documents()->select([
                    'draft_bl',
                    'draft_comercial',
                    'packing_list',
                    'certificate_origin',
                    'certificate_fumigation',
                    'certificate_quality',
                    'certificate_weight',
                    'certificate_seguro',
                    'certificate_phyto',
                    'report',
                    'health_certificate',
                    'non_gmo_certificate'
                ])->first();

                $order->required_docs = [];


                if ($order->drafts) {
                    $order->drafts = $order->drafts->toArray();
                    $order->required_docs = (array) json_decode($order->draft_documents()->first()->required);
                } else {
                    $order->drafts = [
                        'draft_bl' => null,
                        'draft_comercial' => null,
                        'packing_list' => null,
                        'certificate_origin' => null,
                        'certificate_fumigation' => null,
                        'certificate_quality' => null,
                        'certificate_weight' => null,
                        'certificate_seguro' => null,
                        'certificate_phyto' => null,
                        'report' => null,
                        'health_certificate' => null,
                        'non_gmo_certificate' => null,
                    ];
                }

                if (!$order->required_docs) {
                    $order->required_docs = [
                        'draft_bl' => true,
                        'draft_comercial' => true,
                        'packing_list' => true,
                        'certificate_origin' => true,
                        'certificate_fumigation' => true,
                        'certificate_quality' => true,
                        'certificate_weight' => true,
                        'certificate_seguro' => true,
                        'certificate_phyto' => true,
                        'report' => true,
                        'health_certificate' => true,
                        'non_gmo_certificate' => true,
                        'others' => false
                    ];
                }

                $order->originals = [
                    'bl' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        1
                    )->first()) ? $doc->url : null),
                    'comercial' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        2
                    )->first()) ? $doc->url : null),
                    'packing_list' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        3
                    )->first()) ? $doc->url : null),
                    'certificate_origin' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        4
                    )->first()) ? $doc->url : null),
                    'certificate_fumigation' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        5
                    )->first()) ? $doc->url : null),
                    'certificate_quality' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        6
                    )->first()) ? $doc->url : null),
                    'certificate_weight' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        7
                    )->first()) ? $doc->url : null),
                    'certificate_seguro' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        8
                    )->first()) ? $doc->url : null),
                    'certificate_phyto' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        9
                    )->first()) ? $doc->url : null),
                    'report' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        10
                    )->first()) ? $doc->url : null),
                    'health_certificate' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        12
                    )->first()) ? $doc->url : null),
                    'non_gmo_certificate' => (($doc = $order->original_documents()->where(
                        'original_documents_type_id',
                        13
                    )->first()) ? $doc->url : null),
                ];

                $order->draft_others = ($docs = $order->draft_documents()->first() ? json_decode($order->draft_documents()->first()->others) : []);
                $order->original_others = ($docs = $order->original_documents()->where(
                    'original_documents_type_id',
                    11
                )->get()) ?? [];
            }

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->shipping = $shipping;
            }

            $loadings = $order->loadings()->first();
            
            if ($loadings) {
                $order->loadings = $loadings;
                $trucks = $loadings->vehicles()->get();
                if ($trucks && !empty($order->items->first())) {

                    if ($trucks->sum('wheight') == $order->items->first()->net_weight) {
                        if (($container_stuffing = $order->container_stuffing()->first()) && $container_stuffing->stuffing_ending_date) {
                            $unloading_date = Carbon::createFromFormat(
                                'Y-m-d',
                                $container_stuffing->stuffing_ending_date
                            );
                        } else {
                            $unloading_date = null;
                        }

                        if ($request->input('status') == 'loading') {
                            unset($orders[$i]);
                            continue;
                        } elseif ($request->input('status') == 'transit' && (!$unloading_date || $unloading_date->lessThanOrEqualTo(Carbon::now()))) {
                            unset($orders[$i]);
                            continue;
                        } elseif ($request->input('status') == 'release' && (!$unloading_date || $unloading_date->greaterThan(Carbon::now()))) {
                            unset($orders[$i]);
                            continue;
                        }
                    } elseif (($request->input('status') == 'release' || $request->input('status') == 'transit') && $trucks->sum('wheight') < $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    }

                    foreach ($trucks as $truck) {
                        $truck->drivers = $truck->drivers()->first();
                        $truck->carriers = $truck->carriers()->first();
                        $truck->bills = $truck->bills()->get()->toArray();
                    }
                    $order->loadings->vehicles = $trucks;
                }
            }

            $container_stuffing = $order->container_stuffing()->first();

            if ($container_stuffing) {
                $order->container_stuffing = $container_stuffing;
                $order->cntrs_amount = $container_stuffing->qtd_container;
                $order->terminal_observations = $container_stuffing->terminal_observations;
            }

            $order->mapa_docs = [
                'draft_lpco' => null,
                'original_lpco' => null,
                'due' => null
            ];

            $mapa = $order->mapa()->first();

            if ($mapa) {
                $order->mapa = $mapa;
                $order->mapa_docs = [
                    'draft_lpco' => $mapa->lpco_document,
                    'original_lpco' => $mapa->lpco_document_signed,
                    'due' => $mapa->due_document
                ];
            }

            if ($request->input('status') == 'missing_docs' && !$request->input('filter')) {
                $missing_drafts = count($order->drafts) - count(
                    array_filter(
                        $order->drafts,
                        function ($val, $type) use ($order) {
                            return $val || !$order->required_docs[$type];
                        },
                        ARRAY_FILTER_USE_BOTH
                    )
                );

                $missing_originals = count($order->originals) - count(
                    array_filter(
                        $order->originals,
                        function ($val, $type) use ($order) {
                            if ($type == 'bl' || $type == 'comercial') {
                                $type = 'draft_' . $type;
                            }
                            return $val || !$order->required_docs[$type];
                        },
                        ARRAY_FILTER_USE_BOTH
                    )
                );
                $missing_mapa = !$order->mapa_docs['due'] || ($order->required_docs['certificate_phyto'] && (!$order->mapa_docs['draft_lpco'] || !$order->mapa_docs['original_lpco']));

                $missing_others = false;
                if ($order->required_docs['others']) {
                    $missing_draft_others = $order->required_docs['others'] - count($order->draft_others);
                    $missing_original_others = $order->required_docs['others'] - count($order->original_others);

                    $missing_others = ($missing_draft_others >= 0 ? $missing_draft_others : 0) || ($missing_original_others >= 0 ? $missing_original_others : 0);
                }


                if (!$missing_drafts && !$missing_originals && !$missing_mapa && !$missing_others) {
                    unset($orders[$i]);
                    continue;
                }
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            if($order->items){
                $total_container += intval($order->items[0]->container);
            }

        }

        return response()->json([
            'orders' => $orders,
            'total_container' => $total_container,
            'filters' => $request->input('filters'),
            'status' => $request->input('status')
        ]);
    }

    public function listOrders(Order $orders)
    {
        $user = User::getUserLogged();

        $orders = $orders->select('id', 'number')
                     ->where('order_status_id', '!=', 4)
                     ->whereNull('deleted_at')
                     ->where(function ($q) use ($user) {
                        return $q->whereRaw('? = 1', [$user->group->id])
                            ->orWhere('group_id', $user->group->id);
                    })
        
                    ->when($user->roles->first()->name == "Exporter", function ($query) use ($user){
                        return $query->where('exporter_id', $user->id);
                    })
                    ->get()
                    ->makeHidden(['packing', 'full_packing', 'containers_count']);

        return response()->json($orders);
    }

    private function filterOrders($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            return $query->where('number', 'like', '%' . $filter . '%')
                ->orWhere('port_origin', 'like', '%' . $filter . '%')
                ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                ->orWhere('shipment', 'like', '%' . $filter . '%')
                ->orWhereHas('items', function ($query) use ($filter) {
                    return $query->where('description', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('owner', function ($query) use ($filter) {
                    return $query->where('name', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('loadings', function ($query) use ($filter) {
                    return $query->whereHas('vehicles', function ($query) use ($filter) {
                        return $query->where('driver', 'like', '%' . $filter . '%')
                            ->orWhere('plate', 'like', '%' . $filter . '%');
                    })
                    ->orWhere('unloading_location', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('shipping', function ($query) use ($filter) {
                    return $query->where('booking', 'like', '%' . $filter . '%')
                            ->orWhere('shipping_line', 'like', '%' . $filter . '%');
                });
        });
    }

    public function details()
    {
        $user = User::getUserLogged();

        $orders = Order::with([
            'owner',
            'items',
            'loadings',
            'shipping',
            'specifications.specification',
            'loadings.vehicles',
            'group',
            'exporter'
        ])
            ->has('loadings')
            ->where(function ($q) use ($user) {
                $q->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->where('group_id', $user->group->id)
            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user){
                return $query->where('exporter_id', $user->id);
            })
            ->get();
                
        $orderDetails = [];

        $orders->each(function ($order) use (&$orderDetails){
            $order['total_shipped'] = 0;
            $order['number'];
            $order['items'];

            $order['balance'] = 0;

            $order['stuffing_starting_date'] = Carbon::parse($order['container_stuffing']['stuffing_starting_date'])->format('d/m/Y');

            if ($order['loadings']) {

                $weight = 0;

                if ($order['items'] && isset($order['items'][0]) && $order['items'][0]['net_weight']) {
                    $weight = $order['items'][0]['net_weight'];
                }

                if ($order['container_stuffing'] && $order['container_stuffing']['quantity_total']) {
                    $weight = $order['container_stuffing']['quantity_total'];
                }

                $order['balance'] = $weight - $order['total_shipped'];
            }

            if ($order['balance']) {
                $orderDetails[] = $order;
            }
        });

        $ordersDetailSorted = array_values(Arr::sort($orderDetails, function ($value) {
            return $value['balance'];
        }));

        return $ordersDetailSorted;
    }

    private function filterReports($query, $status, $filter)
    {
        return $query->where(function ($query) use ($status, $filter) {
            if ($status == 'first' || !$status) {
                return $query->where('number', 'like', '%' . $filter . '%')
                    ->orWhereHas('items', function ($query) use ($filter) {
                        return $query->where('description', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('loadings', function ($query) use ($filter) {
                        return $query->where('loading_location', 'like', '%' . $filter . '%')
                            ->orWhere('unloading_location', 'like', '%' . $filter . '%');
                    });
            } elseif ($status == 'second') {
                return $query->where('number', 'like', '%' . $filter . '%')
                    ->orWhereHas('items', function ($query) use ($filter) {
                        return $query->where('description', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('loadings', function ($query) use ($filter) {
                        return $query->where('loading_location', 'like', '%' . $filter . '%')
                            ->orWhere('unloading_location', 'like', '%' . $filter . '%')
                            ->orWhereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%')
                                    ->orWhere('note', 'like', '%' . $filter . '%')
                                    ->orWhereHas('carriers', function ($query) use ($filter) {
                                        return $query->where('name', 'like', '%' . $filter . '%');
                                    });
                            });
                    });
            } elseif ($status == 'third') {
                return $query->where('number', 'like', '%' . $filter . '%')
                    ->orWhereHas('owner', function ($query) use ($filter) {
                        return $query->where('name', 'like', '%' . $filter . '%');
                    });
            }
        });
    }

    public function reports(Request $request)
    {
        $status = $request->input('status');
        $user = User::getUserLogged();

        $orders = Order::with(['owner', 'items', 'shipping', 'loadings.vehicles.drivers', 'loadings.vehicles', 'loadings.vehicles.carriers', 'loadings.vehicles.bills','container_stuffing', 'mapa'])
            ->has('owner')
            ->where(function ($q) use ($user) {
                $q->where('group_id', $user->group->id);

            })

            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user){
                return $query->where('exporter_id', $user->id);
            })

            ->when($status == 'first' || !$status, function ($query) {
                return $query->whereHas('shipping', function ($query) {
                    return $query->where('etd', '>=', '2020-01-01');
                });
            })
            ->when($request->has('filters'), function ($query) use ($status, $request) {
                if (is_array($request->input('filters'))) {
                    foreach ($request->input('filters') as $filter) {
                        $this->filterReports($query, $status, $filter);
                    }
                } elseif ($request->input('filters')) {
                    $this->filterReports($query, $status, $request->input('filters'));
                }
            })
            ->orderByDesc('created_at')->get();

        foreach ($orders as $i => $order) {
            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $order->status->name = $shipping->status()->first()->name;
                }
            }

            if ($status == 'first' || !$status) {
                $date = ($order->loadings && $order->loadings->start_truck_loading_date ? Carbon::createFromFormat('Y-m-d', $order->loadings->start_truck_loading_date) : null);
            } elseif ($status == 'second') {
                $date = ($order->loadings && $order->loadings->end_truck_loading_date ? Carbon::createFromFormat('Y-m-d', $order->loadings->end_truck_loading_date) : null);
            } elseif ($status == 'third') {
                $date = ($order->loadings && $order->loadings->date_ptax ? Carbon::createFromFormat('Y-m-d', $order->loadings->date_ptax) : null);
            } else {
                $date = null;
            }

            if ($request->input('initial_date')) {
                if (!$date || $date->lessThan(Carbon::createFromFormat('Y-m-d', $request->input('initial_date')))) {
                    unset($orders[$i]);
                    continue;
                }
            }

            if ($request->input('final_date')) {
                if (!$date || $date->greaterThan(Carbon::createFromFormat('Y-m-d', $request->input('final_date')))) {
                    unset($orders[$i]);
                    continue;
                }
            }
        }

        return response()->json(['orders' => $orders, 'filters' => $request->input('filters'), 'status' => $request->input('status')]);
    }

    private function filterRailway($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            return $query->where('number', 'like', '%' . $filter . '%')
                ->orWhereHas('items', function ($query) use ($filter) {
                    return $query->where('description', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('shipping', function ($query) use ($filter) {
                    return $query->where('booking', 'like', '%' . $filter . '%')
                        ->orWhere('vessel', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('forwarding_agent', function ($query) use ($filter) {
                    return $query->where('name', 'like', '%' . $filter . '%');
                });
        });
    }

    public function railway(Request $request)
    {
        $user = User::getUserLogged();

        $orders = Order::with(['owner', 'items', 'shipping', 'loadings.vehicles.drivers', 'loadings.vehicles.carriers', 'container_stuffing', 'mapa', 'railroads'])
            ->has('owner')
            ->where(function ($q) use ($user) {
                $q->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->where('group_id', $user->group->id)
            ->whereNotNull('railway_agent_id')
            ->when($request->has('initial_date') && $request->input('initial_date'), function ($query) use ($request){
                return $query->whereHas('shipping', function ($query) use ($request){
                    return $query->whereNotNull('loading_deadline')
                        ->where('loading_deadline', '>=', $request->input('initial_date'));
                });
            })
            ->when($request->has('final_date') && $request->input('final_date'), function ($query) use ($request){
                return $query->whereHas('shipping', function ($query) use ($request){
                    return $query->whereNotNull('loading_deadline')
                        ->where('loading_deadline', '<=', $request->input('final_date'));
                });
            })
            ->when($request->has('filters'), function ($query) use ($request){
                if (is_array($request->input('filters'))) {
                    foreach ($request->input('filters') as $filter) {
                        $this->filterRailway($query, $filter);
                    }
                } elseif ($request->input('filters')) {
                    $this->filterRailway($query, $request->input('filters'));
                }
            })
            ->orderByDesc('created_at')->get();

        foreach ($orders as $i => $order) {
            $order->status = $order->order_status()->first();
            $order->forwarding_agent;

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $order->status->name = $shipping->status()->first()->name;
                }
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                $order->fumigation = (object) [
                    'init' => $fumigation->date_of_fumigation_certificate,
                    'end' => $fumigation->date_of_conclusion
                ];
            }
        }

        return response()->json(['orders' => $orders, 'filters' => $request->input('filters')]);
    }

    public function setDocumentoStatus($type, $status, $document, $hash)
    {

        if (!$order = \App\Order::whereRaw('md5(id) = ?', [$hash])->first()) {
            throw new \Exception("Order not found");
        }

        $title = 'Thanks for your answer';
        $message = null;
        $color = 'table-line-highlight';

        switch ($type) {
            case 'draft-documents':
                $status = ($status == 'approved' ? 2 : 3);
                $title = strtoupper('Draft Documents - ' . $title);
                $draft_documents = $order->draft_documents()->first();
                $reason_text = 'Updated by email link API';

                $fields = \Schema::getColumnListing($draft_documents->getTable());

                foreach ($fields as $field) {

                    if ($document != 'all' && $document . '_status' != $field)
                        continue;

                    if (strpos($field, '_status') !== false) {
                        $draft_documents->{$field} = $status;
                    } else if (strpos($field, '_reason') !== false) {
                        $draft_documents->{$field} = $reason_text;
                    }
                }
                $draft_documents->save();

                $message = strtoupper(str_replace('_', ' ', $document) . ' document is  ' . ($status == 2 ? 'Accepted' : 'Rejected'));

                if ($status == 3) {
                    $color = 'red';
                }
                break;

            case 'original-documents':

                $status = ($status == 'approved' ? 3 : 2);
                $title = strtoupper('Original Documents - ' . $title);
                $order
                    ->original_documents()
                    ->where(function ($q) use ($document) {
                        $q
                            ->where('original_documents_type_id', $document)
                            ->orWhereRaw("? = 'all'", [$document]);
                    })
                    ->update(['original_documents_status_id' => $status]);

                if ($document == 'all') {
                    $document = 'All';
                } else {
                    $document = $order->original_documents()->where('original_documents_type_id', $document)->first()->document_type->name;
                }
                $message = strtoupper($document . ' document is  ' . ($status == 3 ? 'Accepted' : 'Rejected'));

                if ($status == 2) {
                    $color = 'red';
                }
                break;

            default:

                break;
        }

        return view('documents.order_status', ['order' => $order, 'title' => $title, 'message' => $message, 'status' => $status, 'color' => $color]);
    }

    public function containers(Request $request)
    {
        $user = User::getUserLogged();
        if (!$user || !$user->group) {
            return response()->json(['message' => 'User or user group not found'], Response::HTTP_UNAUTHORIZED);
        }
    
        $filters = $request->input('filters');
        $final_date = $request->input('final_date');
        $initial_date = $request->input('initial_date');
        $perPage = $request->input('perPage', 15);
        $status = $request->input('status');
        $user_role_name = $user->roles->first()->name;
    
        Log::info('User information:', ['user_id' => $user->id, 'group_id' => $user->group->id, 'role' => $user_role_name]);
    
        $ordersQuery = Order::with('container_stuffing')
            ->with(['shipping' => function ($query) {
                $query->withCount('containers');
            }])
            ->with('contract_provisions.provider_contract.service')
            ->where('group_id', $user->group->id)
            ->when($filters, function ($query) use ($filters) {
                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        $query->where(function ($query) use ($filter) {
                            $query->where('number', 'like', '%' . $filter . '%')
                                ->orWhereHas('container_stuffing', function ($query) use ($filter) {
                                    $query->where('dispatch_place_name', 'like', '%' . $filter . '%');
                                })
                                ->orWhereHas('shipping', function ($query) use ($filter) {
                                    $query->where('vessel', 'like', '%' . $filter . '%')
                                        ->orWhere('shipping_line', 'like', '%' . $filter . '%')
                                        ->orWhereHas('containers', function ($query) use ($filter) {
                                            $query->where('name', 'like', '%' . $filter . '%');
                                        });
                                });
                        });
                    }
                }
            })
            ->when($initial_date, function ($query) use ($initial_date) {
                $initial_date = Carbon::parse($initial_date)->subDay()->format('Y-m-d');
                $query->whereHas('shipping.containers', function ($query) use ($initial_date) {
                    $query->where('return_date', '>', $initial_date);
                });
            })
            ->when($final_date, function ($query) use ($final_date) {
                $final_date = Carbon::parse($final_date)->addDay()->format('Y-m-d');
                $query->whereHas('shipping.containers', function ($query) use ($final_date) {
                    $query->where('return_date', '<', $final_date);
                });
            })
            ->orderByDesc('created_at');
    
        $orders = $ordersQuery->get();
    
        Log::info('Orders retrieved:', ['order_count' => $orders->count()]);
    
        $ordersArray = $orders->map(function ($order) use ($status) {
            $orderArray = $order->toArray();
    
            $orderStatus = 'waiting_booking';
            if (!empty($orderArray['shipping'])) {
                $firstShipping = $orderArray['shipping'][0];
                if ($firstShipping['booking'] === null) {
                    $orderStatus = 'waiting_booking';
                } else {
                    $orderStatus = $firstShipping['containers_count'] > 0 ? 'with' : 'without';
                }
            }
            
            $hasNoReturn = false;
            if ($orderStatus === 'with') {
                foreach ($orderArray['shipping'] as $shipping) {
                    foreach ($shipping['containers'] as $container) {
                        if ($container['return_date'] === null) {
                            $hasNoReturn = true;
                            break 2;
                        }
                    }
                }
                if ($hasNoReturn) {
                    $orderStatus = 'no_return';
                }
            }
    
            if ($status && $status !== $orderStatus) {
                return null;
            }
    
            foreach ($orderArray['shipping'] as &$shipping) {
                if (!is_null($shipping['booking'])) {
                    foreach ($shipping['containers'] as &$container) {
                        $freeTime = (int)$shipping['free_time_origin'];
                        $withdrawalDate = Carbon::parse($container['withdrawal_date']);
                        $freeTimeOrigin = $withdrawalDate->copy()->addDays($freeTime);
                        $returnDate = Carbon::parse($container['return_date']);
    
                        $late = $freeTimeOrigin->diffInDays($returnDate);
                        $container['excess_time'] = $returnDate > $freeTimeOrigin ? ($late > 1 ? $late . ' days' : $late . ' day') : 'up to date';
                        $container['free_time_limit'] = $freeTimeOrigin;
                    }
                }
            }
    
            return $orderArray;
        })->filter()->values()->all();
    
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemsPerPage = $perPage;
        $totalItems = count($ordersArray);
    
        $currentPageItems = array_slice($ordersArray, ($currentPage - 1) * $itemsPerPage, $itemsPerPage);
    
        $paginator = new LengthAwarePaginator($currentPageItems, $totalItems, $itemsPerPage, $currentPage);
    
        return response()->json(['orders' => $paginator, 'filters' => $request->input('filters')]);
    }
    
    

    public function get(Request $request, $id)
    {

        $order = Order::with('contract_provisions.provider_contract')->find($id);
        $order->importer_id = $order->owner_id;
        $order->exporter = $order->exporter_id;

        $order->document_contract_signed = null;
        $order->document_proforma_signed = null;
        $order->document_swift_advance = null;
        $order->document_label_model = null;
        $order->document_instructions_documents = null;

        $contract = $order->documents->where('document_type_id', 1)->first();
        if ($contract)
            $order->contract = $contract->toArray()['url'];

        $contract_signed = $order->documents->where('document_type_id', 2)->first();
        if ($contract_signed)
            $order->document_contract_signed = $contract_signed->toArray()['url'];

        $proforma = $order->documents->where('document_type_id', 3)->first();
        if ($proforma)
            $order->proforma = $proforma->toArray()['url'];

        $proforma_signed = $order->documents->where('document_type_id', 4)->first();
        if ($proforma_signed)
            $order->document_proforma_signed = $proforma_signed->toArray()['url'];

        $swift_advance = $order->documents->where('document_type_id', 5)->first();
        if ($swift_advance)
            $order->document_swift_advance = $swift_advance->toArray()['url'];

        $instructions_documents = $order->documents->where('document_type_id', 6)->first();
        if ($instructions_documents)
            $order->document_instructions_documents = $instructions_documents->toArray()['url'];

        $label_model = $order->documents->where('document_type_id', 7)->first();
        if ($label_model)
            $order->document_label_model = $label_model->toArray()['url'];

        $order->items = $order->items->toArray();
        $order->mapa;

        $order->orders_document_order;
        $order->specifications;

        return response()->json($order);
    }

    public function newOrder(NewOrderRequests $request, NewOrderAction $newOrderAction)
    {
        $user = User::getUserLogged();
        $requestData = $request->validated();
        $requestData['user_id'] = $user->id;
        $requestData['owner_id'] = $requestData['importer_id'];

        $response = $newOrderAction($requestData);
        return response()->json($response);
    }

    public function put(NewOrderRequests $request, $id)
    {
        // $validator = Validator::make($request->all(), $this->getRules(0), $this->messages);

        // if ($validator->fails()) {
        //     return response()->json(['error' => 'Erro de validação', 'errors' => $validator->errors()], 422);
        // }
        // $requestData = $request->validated();
        $order = Order::find($id);
        
        $order->owner_id = $request->input('importer_id');
        $order->exporter_id = $request->input('exporter_id');
        $order->signature_id = $request->input('signature_id');
        $order->swift_advance = $request->input('swift_advance');
        $order->label = $request->input('label');
        $order->user_id = $request->get('user_id');
        $order->fumigation_id = $request->get('fumigation_id');
        $order->broker_id = $request->get('broker_id');
        $order->quality_id = $request->get('quality_id');
        $order->seguro_id = $request->get('seguro_id');
        $order->weight_id = $request->get('weight_id');
        $order->inspection_id = $request->get('inspection_id');
        $order->forwarding_agent_id = $request->get('forwarding_agent_id');
        $order->terminal_agent_id = $request->get('terminal_agent_id');
        $order->railway_agent_id = $request->get('railway_agent_id');
        $order->number = $request->get('number');
        $order->invoce_advance_status = $request->get('invoce_advance_status');
        $order->invoce_advance_value = $request->get('invoce_advance_value');
        $order->invoce_total = $request->get('invoce_total');
        $order->invoce_balance = $request->get('invoce_balance');
        $order->packing = $request->get('full_packing');
        $order->port_origin = $request->get('port_origin');
        $order->port_destiny = $request->get('port_destiny');
        $order->payment_conditions = $request->get('payment_conditions');
        $order->incoterm = $request->get('incoterm');
        $order->banks_id = $request->get('banks_id');
        $order->notify_id = $request->get('notify_id');
        $order->consignee_id = $request->get('consignee_id');
        $order->shipment = $request->get('shipment');
        $order->transportion = $request->get('transportion');
        $order->container_type = $request->get('container_type');
        $order->unit_comission = ($request->get('unit_comission') ? $request->get('unit_comission') : 0);
        $order->note = $request->get('note');

        $order->save();

        if ($order->exporter) {
            $order->group_id = $order->exporter->group_id;
            $order->save();
        }

        $items = $order->items();

        $ordersSpecifications = new OrderSpecification();
        $ordersSpecifications->where('orders_id', $order->id)->delete();

        if ($request->input('specifications')) {
            foreach ($request->input('specifications') as $specification) {
                $ordersSpecifications->insert([
                    'orders_id' => $order->id,
                    'specifications_id' => $specification,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        $documentOrder = new OrderDocumentOrder();
        $documentOrder->where('orders_id', $order->id)->delete();

        if ($request->input('document')) {
            foreach ($request->input('document') as $document_order) {
                $documentOrder->insert([
                    'orders_id' => $order->id,
                    'document_order_id' => $document_order,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        $order['document'] = $request->input('document');

        $n_items = $request->input('items');
        $n_items = array_filter($n_items, function ($a) {
            return array_key_exists('id', $a);
        });

        $n_items = array_map(function ($a) {
            return $a['id'];
        }, $n_items);

        $items->whereNotIn('id', $n_items)->delete();

        foreach ($request->input('items') as $item_data) {
            $item = new \App\Item();
            if (array_key_exists('id', $item_data)) {
                $item = \App\Item::find($item_data['id']);
            }

            $item->description      = $item_data['description'];
            $item->botanical_name   = isset($item_data['botanical_name']) ? $item_data['botanical_name'] : null;
            $item->crop_year        = $item_data['crop_year'];
            $item->quantity         = $item_data['quantity'];
            $item->unit_price       = $item_data['unit_price'];
            $item->total_price      = $item_data['total_price'];
            $item->value            = $item_data['value'];
            $item->total_bags       = $item_data['total_bags'];
            $item->net_weight       = $item_data['net_weight'];
            $item->gross_weight     = $item_data['gross_weight'];
            $item->advance_payment  = $item_data['advance_payment'];
            $item->container        = $item_data['container'];
            $item->hs_code          = $item_data['hs_code'];

            $order->items()->save($item);
        }
    
        ($this->createContractAction)($order->toArray());
        ($this->createProformaAction)($order->toArray());

        return response()->json(['success' => 'Order is successfully updated.']);
    }

    public function delete(\Request $request, $id)
    {
        $order = \App\Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'This order cannot be founded.']);
        }

        $order->delete();

        return response()->json(['success' => 'Order is successfully deleted.']);
    }

    public function myOrders(\Request $request)
    {

        $user = \App\User::find(\Request::get('user_id'));
        $userLogged = \App\User::getUserLogged();




        if ($user->hasRole('Broker')) {
            $orders = $user->brokerOrders()->where(function ($q) use ($userLogged) {
                $q
                    ->whereRaw('? = 1', $userLogged->group->id)
                    ->orWhere('group_id', $userLogged->group->id);

            })->get();

        } else {
            $orders = $user->orders()->where(function ($q) use ($userLogged) {
                $q
                    ->whereRaw('? = 1', $userLogged->group->id)
                    ->orWhere('group_id', $userLogged->group->id);

            })->get();

        }

        foreach ($orders as $order) {
            $order->status = $order->order_status()->first();
            $order->shipping = $order->shipping()->first();
            $order->items = $order->items()->get();

            $shipping = $order->shipping()->first();

            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }
        }

        return response()->json(array_values($orders->toArray()));
    }

    public function myOrdersPut(\Request $request)
    {

        $order = \App\Order::find(\Request::input('id'));

        $validator = \Validator::make(\Request::all(), $this->getRulesUser($order), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            if ($this->hasNewFile($order)) {
                \Mail::to('rq.godoy@gmail.com')->send(new \App\Mail\NewDocumentSended([
                    'name' => $order->owner->name
                ]));

                $order->order_status_id = 2;
                $order->save();
            }
        } catch (\Exception $e) {
        }

        $this->updateDocument(2, \Request::input('document_contract_signed'), $order);
        $this->updateDocument(4, \Request::input('document_proforma_signed'), $order);
        $this->updateDocument(5, \Request::input('document_swift_advance'), $order);
        $this->updateDocument(6, \Request::input('document_instructions_documents'), $order);
        $this->updateDocument(7, \Request::input('document_label_model'), $order);

        return response()->json(['success' => 'Order is successfully updated.']);
    }

    public function getCertificateOfSecurity()
    {

        $user = \App\User::find(\Request::get('user_id'));
        $orders = $user->certificateOfSeguroOrders()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'missing_docs' && !\Request::input('filter'), function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')->where('etd', '>', '2020-01-01');
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as $i => &$order) {
            $order->owner = $order->owner_name;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $loadings = $order->loadings()->first();
            if ($loadings) {
                $order->loadings = $loadings;
                $trucks = $loadings->vehicles()->get();
                if ($trucks) {
                    if (\Request::input('status') == 'loading' && $trucks->sum('wheight') == $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } elseif (\Request::input('status') == 'release' && $trucks->sum('wheight') < $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } else {
                        foreach ($trucks as $truck) {
                            $truck->drivers = $truck->drivers()->first();
                        }
                        $order->loadings->vehicles = $trucks;
                    }
                }
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            if (\Request::input('status') == 'missing_docs') {
                $order->drafts = $order->draft_documents()->select([
                    'draft_bl',
                    'certificate_seguro'
                ])->first();

                $order->required_docs = [];
                if ($order->drafts) {
                    $order->drafts = $order->drafts->toArray();
                    $order->required_docs = (array) json_decode($order->draft_documents()->first()->required);
                }

                if (!$order->required_docs) {
                    $order->required_docs = [
                        'draft_bl' => true,
                        'certificate_seguro' => true
                    ];
                }

                if (!$order->drafts) {
                    $order->drafts = [
                        'draft_bl' => null,
                        'certificate_seguro' => null
                    ];
                }

                $missing_drafts = count($order->drafts) - count(array_filter($order->drafts, function ($val, $type) use ($order) {
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));


                if (!$missing_drafts && !\Request::input('filter')) {
                    unset($orders[$i]);
                    continue;
                }
            }

            unset($order->document);
        }

        foreach ($orders as &$order) {
            $order->owner = $order->owner;
            $order->document = $order->certificateOfSeguro();
            $order->status = $order->order_status()->first();
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
    }

    public function getCertificateOfFumigation()
    {
        $user = \App\User::find(\Request::get('user_id'));
        $orders = $user->certificateOfFumigationOrders()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'missing_docs' && !\Request::input('filter'), function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')->where('etd', '>', '2020-01-01');
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as $i => &$order) {
            $order->owner = $order->owner;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $loadings = $order->loadings()->first();
            if ($loadings) {
                $order->loadings = $loadings;
                $trucks = $loadings->vehicles()->get();
                if ($trucks) {
                    if (\Request::input('status') == 'loading' && $trucks->sum('wheight') == $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } elseif (\Request::input('status') == 'release' && $trucks->sum('wheight') < $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } else {
                        foreach ($trucks as $truck) {
                            $truck->drivers = $truck->drivers()->first();
                        }
                        $order->loadings->vehicles = $trucks;
                    }
                }
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            if (\Request::input('status') == 'missing_docs') {
                $order->drafts = $order->draft_documents()->select([
                    'draft_bl',
                    'certificate_fumigation'
                ])->first();

                $order->required_docs = [];
                if ($order->drafts) {
                    $order->drafts = $order->drafts->toArray();
                    $order->required_docs = (array) json_decode($order->draft_documents()->first()->required);
                }

                if (!$order->required_docs) {
                    $order->required_docs = [
                        'draft_bl' => true,
                        'certificate_fumigation' => true
                    ];
                }

                if (!$order->drafts) {
                    $order->drafts = [
                        'draft_bl' => null,
                        'certificate_fumigation' => null
                    ];
                }

                $order->originals = [
                    'bl' => (($doc = $order->original_documents()->where('original_documents_type_id', 1)->first()) ? $doc->url : null),
                    'certificate_fumigation' => (($doc = $order->original_documents()->where('original_documents_type_id', 5)->first()) ? $doc->url : null)
                ];

                $missing_drafts = count($order->drafts) - count(array_filter($order->drafts, function ($val, $type) use ($order) {
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));
                $missing_originals = count($order->originals) - count(array_filter($order->originals, function ($val, $type) use ($order) {
                    if ($type == 'bl') {
                        $type = 'draft_' . $type;
                    }
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));


                if (!$missing_drafts && !$missing_originals && !\Request::input('filter')) {
                    unset($orders[$i]);
                    continue;
                }
            }

            unset($order->document);
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);

        // foreach ($orders as &$order) {
        //     $order->owner = $order->owner;
        //     $order->document = $order->certificateOfFumigation();
        //     $order->fumigation = $order->fumigation()->first();
        //     $order->status = $order->order_status()->first();
        // }

        // $orders->filter(function ( $order ) {
        //     $drafts = $order->draft_documents()->first();
        //     return !!$drafts;
        // });

        // return response()->json($orders);
    }

    public function getCertificateOfQuality()
    {
        $user = \App\User::find(\Request::get('user_id'));
        $orders = $user->certificateOfQualityOrders()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as &$order) {
            $order->owner = $order->owner;
            $order->document = $order->certificateOfQuality();
            $order->status = $order->order_status()->first();
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
    }

    public function getCertificateOfWeight()
    {
        $user = \App\User::find(\Request::get('user_id'));
        $orders = $user->certificateOfWeightOrders()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();
        foreach ($orders as &$order) {
            $order->owner = $order->owner;
            $order->document = $order->certificateOfWeight();
            $order->status = $order->order_status()->first();
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
    }

    public function postCertificateOfSecurity()
    {
        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        $order->document = $order->certificateOfSeguro()->first();

        $order->document->certificate_seguro = \Request::input('document_url');
        $order->document->certificate_seguro_status = 1;
        $order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function postCertificateOfFumigation()
    {
        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        $order->document = $order->certificateOfFumigation()->first();

        if (!$order->document) {
        }

        $order->document->certificate_fumigation = \Request::input('document_url');
        $order->document->certificate_fumigation_status = 1;
        $order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function postCertificateOfQuality()
    {
        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        $order->document = $order->certificateOfQuality()->first();

        if (!$order->document) {
        }

        $order->document->certificate_quality = \Request::input('document_url');
        $order->document->certificate_quality_status = 1;
        $order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function postCertificateOfWeight()
    {
        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        $order->document = $order->certificateOfWeight()->first();

        if (!$order->document) {
        }

        $order->document->certificate_weight = \Request::input('document_url');
        $order->document->certificate_weight_status = 1;
        $order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function getInspectionAgency(\Request $request)
    {
        $user = \App\User::find(\Request::get('user_id'));

        $orders = $user->inspectionAgency()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'missing_docs' && !\Request::input('filter'), function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')->where('etd', '>', '2020-01-01');
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as $i => &$order) {
            $order->owner = $order->owner;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $loadings = $order->loadings()->first();
            if ($loadings) {
                $order->loadings = $loadings;
                $trucks = $loadings->vehicles()->get();
                if ($trucks) {
                    if (\Request::input('status') == 'loading' && $trucks->sum('wheight') == $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } elseif (\Request::input('status') == 'release' && $trucks->sum('wheight') < $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } else {
                        foreach ($trucks as $truck) {
                            $truck->drivers = $truck->drivers()->first();
                        }
                        $order->loadings->vehicles = $trucks;
                    }
                }
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            if (\Request::input('status') == 'missing_docs') {
                $order->drafts = $order->draft_documents()->select([
                    'draft_bl',
                    'certificate_weight',
                    'certificate_quality',
                    'report',
                    'health_certificate',
                    'non_gmo_certificate'
                ])->first();

                $order->required_docs = [];
                if ($order->drafts) {
                    $order->drafts = $order->drafts->toArray();
                    $order->required_docs = (array) json_decode($order->draft_documents()->first()->required);
                }

                if (!$order->required_docs) {
                    $order->required_docs = [
                        'draft_bl' => true,
                        'certificate_weight' => true,
                        'certificate_quality' => true,
                        'report' => true,
                        'health_certificate' => true,
                        'non_gmo_certificate' => true,
                    ];
                }

                if (!$order->drafts) {
                    $order->drafts = [
                        'draft_bl' => null,
                        'certificate_weight' => null,
                        'certificate_quality' => null,
                        'report' => null,
                        'non_gmo_certificate' => null,
                    ];
                }

                $order->originals = [
                    'bl' => (($doc = $order->original_documents()->where('original_documents_type_id', 1)->first()) ? $doc->url : null),
                    'certificate_quality' => (($doc = $order->original_documents()->where('original_documents_type_id', 6)->first()) ? $doc->url : null),
                    'certificate_weight' => (($doc = $order->original_documents()->where('original_documents_type_id', 7)->first()) ? $doc->url : null),
                    'report' => (($doc = $order->original_documents()->where('original_documents_type_id', 10)->first()) ? $doc->url : null),
                    'health_certificate' => (($doc = $order->original_documents()->where('original_documents_type_id', 12)->first()) ? $doc->url : null),
                    'non_gmo_certificate' => (($doc = $order->original_documents()->where('original_documents_type_id', 13)->first()) ? $doc->url : null)
                ];

                $missing_drafts = count($order->drafts) - count(array_filter($order->drafts, function ($val, $type) use ($order) {
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));
                $missing_originals = count($order->originals) - count(array_filter($order->originals, function ($val, $type) use ($order) {
                    if ($type == 'bl') {
                        $type = 'draft_' . $type;
                    }
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));


                if (!$missing_drafts && !$missing_originals && !\Request::input('filter')) {
                    unset($orders[$i]);
                    continue;
                }
            }

            unset($order->document);
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
        // foreach ($orders as &$order) {
        //     $order->owner = $order->owner;
        //     $order->document = $order->inspectionAgency();
        //     $order->inspctionAgency = $order->inspectionAgency();

        //     $order->inspection_agency = $order->inspection_agency()->first();
        //     $order->status = $order->order_status()->first();
        // }

        // return response()->json($orders);
    }

    public function postInspectionAgency(\Request $request)
    {
        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        $order->document = $order->inspectionAgency()->first();

        $order->document->certificate_weight = \Request::input('certificate_weight');
        $order->document->certificate_weight_status = 1;
        $order->document->certificate_quality = \Request::input('certificate_quality');
        $order->document->certificate_quality_status = 1;
        $order->document->report = \Request::input('report');
        $order->document->report_status = 1;
        $order->document->save();

        $inspection_agency = $order->inspection_agency()->first();
        $inspection_agency->inspection_start_datetime = \Request::input('inspection_start_datetime');
        $inspection_agency->inspection_end_datetime = \Request::input('inspection_end_datetime');
        $inspection_agency->save();

        if ($this->hasNewFile($order)) {
            \Mail::to('rq.godoy@gmail.com')->send(new \App\Mail\NewDocumentSended([
                'name' => $order->owner->name
            ]));

            $order->order_status_id = 2;
            $order->save();
        }

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function getMapa(\Request $request)
    {

        $user = \App\User::find(\Request::get('user_id'));
        $orders = $user->mapa()
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'missing_docs' && !\Request::input('filter'), function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')->where('etd', '>', '2020-01-01');
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as $i => &$order) {
            $order->owner = $order->owner;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $loadings = $order->loadings()->first();
            if ($loadings) {
                $order->loadings = $loadings;
                $trucks = $loadings->vehicles()->get();
                if ($trucks) {
                    if (\Request::input('status') == 'loading' && $trucks->sum('wheight') == $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } elseif (\Request::input('status') == 'release' && $trucks->sum('wheight') < $order->items->first()->net_weight) {
                        unset($orders[$i]);
                        continue;
                    } else {
                        foreach ($trucks as $truck) {
                            $truck->drivers = $truck->drivers()->first();
                        }
                        $order->loadings->vehicles = $trucks;
                    }
                }
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $order->mapa_docs = [
                'draft_lpco' => null,
                'original_lpco' => null,
                'due' => null
            ];

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
                $order->mapa_docs = [
                    'draft_lpco' => $mapa->lpco_document,
                    'original_lpco' => $mapa->lpco_document_signed,
                    'due' => $mapa->due_document
                ];
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            if (\Request::input('status') == 'missing_docs') {
                $order->drafts = $order->draft_documents()->select([
                    'draft_bl',
                    'certificate_phyto'
                ])->first();

                $order->required_docs = [];
                if ($order->drafts) {
                    $order->drafts = $order->drafts->toArray();
                    $order->required_docs = (array) json_decode($order->draft_documents()->first()->required);
                }

                if (!$order->required_docs) {
                    $order->required_docs = [
                        'draft_bl' => true,
                        'certificate_phyto' => true
                    ];
                }

                if (!$order->drafts) {
                    $order->drafts = [
                        'draft_bl' => null,
                        'certificate_phyto' => null
                    ];
                }

                $order->originals = [
                    'bl' => (($doc = $order->original_documents()->where('original_documents_type_id', 1)->first()) ? $doc->url : null),
                    'certificate_phyto' => (($doc = $order->original_documents()->where('original_documents_type_id', 9)->first()) ? $doc->url : null)
                ];

                $missing_drafts = count($order->drafts) - count(array_filter($order->drafts, function ($val, $type) use ($order) {
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));
                $missing_originals = count($order->originals) - count(array_filter($order->originals, function ($val, $type) use ($order) {
                    if ($type == 'bl') {
                        $type = 'draft_' . $type;
                    }
                    return $val || !$order->required_docs[$type];
                }, ARRAY_FILTER_USE_BOTH));
                $missing_mapa = !$order->mapa_docs['due'] || ($order->required_docs['certificate_phyto'] && (!$order->mapa_docs['draft_lpco'] || !$order->mapa_docs['original_lpco']));


                if (!$missing_drafts && !$missing_originals && !$missing_mapa && !\Request::input('filter')) {
                    unset($orders[$i]);
                    continue;
                }
            }

            unset($order->document);
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
        // foreach ($orders as &$order) {
        //     $order->owner = $order->owner;
        //     $order->document = $order->mapa()->get();

        //     $draft = $order->draft_documents()->first();

        //     if ($draft) {
        //         $order->draft_phyto_document = $draft->certificate_phyto;
        //     }

        //     $order->original_phyto_document = $order->original_documents()->where('original_documents_type_id', 9)->first();

        //     $order->status = $order->order_status()->first();
        // }

        // return response()->json($orders);
    }

    public function postMapa(\Request $request)
    {

        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        //$orders = $user->mapa()->first();
        $mapa = $order->mapa()->first();
        $mapa->mapa_document_signed = \Request::input('mapa_document_signed');
        $mapa->save();
        //$order->document = $order->inspectionAgency()->first();

        //$order->document->certificate_weight = \Request::input('certificate_weight');
        //$order->document->certificate_weight_status = 1;
        //$order->document->certificate_quality = \Request::input('certificate_quality');
        //$order->document->certificate_quality_status = 1;
        //$order->document->report = \Request::input('report');
        //$order->document->report_status = 1;
        //$order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    public function getTerminal(\Request $request)
    {
        $user = \App\User::find(\Request::get('user_id'));
        $orders = \App\Order::where('terminal_agent_id', $user->id)
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();
        /*foreach ($orders as &$order) {
            $order->owner = $order->owner;

            //$order->document = $order->mapa()->get();
            //$order->draft_phyto_document = $order->draft_documents()->first()->certificate_phyto;
            //$order->original_phyto_document = $order->original_documents()->where('original_documents_type_id', 9)->first();
            //$order->status = $order->order_status()->first();

        }

        return response()->json($orders);*/

        foreach ($orders as &$order) {
            $order->owner = $order->owner;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            unset($order->document);
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
    }

    public function postTerminal(\Request $request)
    {

        $order = \App\Order::find(\Request::input('order_id'));

        $order->owner = $order->owner;
        //$orders = $user->mapa()->first();
        $mapa = $order->mapa()->first();
        $mapa->mapa_document_signed = \Request::input('mapa_document_signed');
        $mapa->save();
        //$order->document = $order->inspectionAgency()->first();

        //$order->document->certificate_weight = \Request::input('certificate_weight');
        //$order->document->certificate_weight_status = 1;
        //$order->document->certificate_quality = \Request::input('certificate_quality');
        //$order->document->certificate_quality_status = 1;
        //$order->document->report = \Request::input('report');
        //$order->document->report_status = 1;
        //$order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    protected function getRailway(\Request $request)
    {

        $user = \App\User::find(\Request::get('user_id'));
        $orders = \App\Order::where('railway_agent_id', $user->id)
            ->when(\Request::input('status') == 'pending', function ($query) {
                return $query->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when(\Request::input('status') == 'loading' || \Request::input('status') == 'release', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('status') == 'aftership', function ($query) {
                return $query->has('loadings')->whereHas('shipping', function ($query) {
                    return $query->whereNotNull('booking')
                        ->where('etd', '<=', Carbon::now())
                        ->where('eta', '>=', Carbon::now());
                });
            })
            ->when(\Request::input('filter'), function ($query) {
                return $query->where(function ($query) {
                    $filter = \Request::input('filter');

                    return $query->where('number', 'like', '%' . $filter . '%')
                        ->orWhere('port_origin', 'like', '%' . $filter . '%')
                        ->orWhere('port_destiny', 'like', '%' . $filter . '%')
                        ->orWhere('shipment', 'like', '%' . $filter . '%')
                        ->orWhereHas('items', function ($query) use ($filter) {
                            return $query->where('description', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('owner', function ($query) use ($filter) {
                            return $query->where('name', 'like', '%' . $filter . '%');
                        })
                        ->orWhereHas('loadings', function ($query) use ($filter) {
                            return $query->whereHas('vehicles', function ($query) use ($filter) {
                                return $query->where('driver', 'like', '%' . $filter . '%')
                                    ->orWhere('plate', 'like', '%' . $filter . '%');
                            });
                        })
                        ->orWhereHas('shipping', function ($query) use ($filter) {
                            return $query->where('booking', 'like', '%' . $filter . '%');
                        });
                });
            })
            ->get();

        foreach ($orders as &$order) {
            $order->owner = $order->owner;
            $order->document_contract_signed = null;
            $order->document_proforma_signed = null;
            $order->document_swift_advance = null;
            $order->document_label_model = null;
            $order->document_instructions_documents = null;

            $order->items = $order->items()->get();

            $contract_signed = $order->documents->where('document_type_id', 2)->first();
            if ($contract_signed)
                $order->document_contract_signed = $contract_signed->toArray();

            $proforma_signed = $order->documents->where('document_type_id', 4)->first();
            if ($proforma_signed)
                $order->document_proforma_signed = $proforma_signed->toArray();

            $swift_advance = $order->documents->where('document_type_id', 5)->first();
            if ($swift_advance)
                $order->document_swift_advance = $swift_advance->toArray();

            $instructions_documents = $order->documents->where('document_type_id', 6)->first();
            if ($instructions_documents)
                $order->document_instructions_documents = $instructions_documents->toArray();

            $label_model = $order->documents->where('document_type_id', 7)->first();
            if ($label_model)
                $order->document_label_model = $label_model->toArray();

            $order->status = $order->order_status()->first();

            if ($order->status->id == 3) {
                $shipping = $order->shipping()->first();
                if ($shipping) {
                    $status = $shipping->status()->first();
                    $order->status->name = $status->name;
                }
            }

            $shipping = $order->shipping()->first();
            if ($shipping) {
                $order->booking = $shipping->booking;
                $order->draft_deadline = $shipping->draft_deadline;
                $order->loading_deadline = $shipping->loading_deadline;
                $order->shipping = $shipping;
            }

            $container_stuffing = $order->container_stuffing()->first();
            if ($container_stuffing) {
                $order->cntrs_amount = $container_stuffing->qtd_container;
            }

            $mapa = $order->mapa()->first();
            if ($mapa) {
                $order->mapa = $mapa->inspection_date;
            }

            $order->fumigation = (object) [];
            $fumigation = $order->fumigation()->first();
            if ($fumigation) {
                if ($container_stuffing) {
                    $order->fumigation = (object) [
                        'init' => $fumigation->date_of_fumigation_certificate,
                        'end' => $fumigation->date_of_conclusion
                    ];
                }
            }

            unset($order->document);
        }

        return response()->json(['orders' => $orders, 'filter' => \Request::input('filter')]);
    }

    protected function postRailway(\Request $request)
    {
        throw new Exception("Not implemented");
    }

    public function getMapaById(\Request $request, $order_id)
    {
        $user = \App\User::find(\Request::get('user_id'));
        $order = \App\Order::find($order_id);

        $order->owner = $order->owner;
        $order->mapa = $order->mapa()->first();
        $drafts = $order->draft_documents()->first();
        if ($drafts) {
            $order->mapa->phyto_certificate = $drafts->certificate_phyto;
        }

        $original = $order->original_documents()->where('original_documents_type_id', 9)->first();

        if ($original) {
            $order->mapa->phyto_certificate_signed = $original->url;
        }


        $order->status = $order->order_status()->first();

        return response()->json($order);
    }

    public function postMapaById(\Request $request, $order_id)
    {

        $order = \App\Order::find($order_id);

        $order->owner = $order->owner;
        //$orders = $user->mapa()->first();
        $mapa = $order->mapa()->first();
        $mapa->mapa_document_signed = \Request::input('mapa_document_signed');
        $mapa->save();
        //$order->document = $order->inspectionAgency()->first();

        //$order->document->certificate_weight = \Request::input('certificate_weight');
        //$order->document->certificate_weight_status = 1;
        //$order->document->certificate_quality = \Request::input('certificate_quality');
        //$order->document->certificate_quality_status = 1;
        //$order->document->report = \Request::input('report');
        //$order->document->report_status = 1;
        //$order->document->save();

        return response()->json(['success' => 'Document successfully updated!']);
    }

    protected function hasNewFile($order)
    {

        $document_contract_signed = 1;
        $document_proforma_signed = 1;
        $document_swift_advance = 1;
        $document_instructions_documents = 1;
        $document_label_model = 1;

        if (\Request::input('document_contract_signed'))
            $document_contract_signed = count(\App\Document::where('url', \Request::input('document_contract_signed'))->where('document_type_id', 2)->where('order_id', $order->id)->get());

        if (\Request::input('document_proforma_signed'))
            $document_proforma_signed = count(\App\Document::where('url', \Request::input('document_proforma_signed'))->where('document_type_id', 4)->where('order_id', $order->id)->get());

        if (\Request::input('document_swift_advance'))
            $document_swift_advance = count(\App\Document::where('url', \Request::input('document_swift_advance'))->where('document_type_id', 5)->where('order_id', $order->id)->get());

        if (\Request::input('document_instructions_documents'))
            $document_instructions_documents = count(\App\Document::where('url', \Request::input('document_instructions_documents'))->where('document_type_id', 6)->where('order_id', $order->id)->get());

        if (\Request::input('document_label_model'))
            $document_label_model = count(\App\Document::where('url', \Request::input('document_label_model'))->where('document_type_id', 7)->where('order_id', $order->id)->get());

        return
            $document_contract_signed == 0 ||
            $document_proforma_signed == 0 ||
            $document_swift_advance == 0 ||
            $document_instructions_documents == 0 ||
            $document_label_model == 0;
    }

    protected function updateDocument($document_type_id, $document_file, $order)
    {

        $document = $order->documents()->where('document_type_id', $document_type_id)->first();
        if (!$document)
            $document = new \App\Document();
        if (!$document_file) {
            $document->delete();
            return;
        }
        if ($document->url == $document_file)
            return;

        $document->url = $document_file;
        $document->order_id = $order->id;
        $document->document_type_id = $document_type_id;
        $document->document_status_id = 1;
        $document->save();
    }

    protected function getRules($id)
    {
        $rule = [
            'importer_id' => 'required',
            'swift_advance' => 'required',
            'items' => 'required',
            'number' => 'required',
            'full_packing' => 'required',
            'label' => 'required',
            'port_origin' => 'required',
            'port_destiny' => 'required',
            'payment_conditions' => 'required',
            'banks_id' => 'required',
            'specifications' => 'required',
            'document' => 'required',
            'notify_id' => 'required',
            'consignee_id' => 'required',
            'transportion' => 'required',
            'incoterm' => 'required',
            'shipment' => 'required',
            'signature_id' => 'required',
            'container_type' => 'required',
        ];

        return $rule;
    }

    protected function getRulesUser($order)
    {
        return [];
        $rule = [
            'document_contract_signed' => 'required',
            'document_proforma_signed' => 'required'
        ];

        if ($order->swift_advance) {
            $rule['document_swift_advance'] = 'required';
        }

        return $rule;
    }

    public function updateTransaction(\Request $request, $id)
    {

        $order = \App\Order::find($id);

        $order->nf = \Request::input('nf');
        $order->expiry_date = \Request::input('expiry_date');

        $order->save();

        return response()->json(['suceess' => true]);
    }

    public function storeContractProvision(Request $request, int $id)
    {

        $contract = ContractProvision::create(
            [
                'provider_contract_id' => $request->get('provider_contract_id'),
                'order_id' => $id,
                'status' => 1,
            ]
        );

        return response()->json($contract->load('provider_contract'));
    }

    public function updateContractProvision(Request $request, int $id, int $contractID)
    {

        $contract = ContractProvision::find($contractID);
        $contract->provider_contract_id = $request->get('id');
        $contract->save();

        return response()->json($contract->load('provider_contract'));
    }

    public function deleteContractProvision(Request $request, int $id, int $contractID)
    {
        $contract = ContractProvision::find($contractID);
        $contract->delete();

        return response()->json(['sucess' => true]);
    }

    public function getHistoryLogs(Request $request) : Response
    {
        $subject_type = $request->query('subject_type');
        $subject_id = $request->query('subject_id');
    
        $history_log = HistoryLog::where('subject_type', $subject_type)
                                 ->where('subject_id', $subject_id)
                                 ->get()->toJson();

        if ($history_log) {
             return response($history_log, Response::HTTP_OK);
        }

        return response([
            'message' => 'History Log not found'
        ], Response::HTTP_NOT_FOUND);

    }

    public function storeNote(Request $request, int $orderId) : Response
    {
        $note = $request['note'];

        $order = Order::find($orderId);

        if ($order) {
            $causer = User::getUserLogged();
            $order->note = $note;
            $order->save();

            $log = json_encode([
                'causer_name' => $causer->name,
                'id' => $orderId,
                'note' => $note
            ]);

            $causer_id = User::getUserLogged()->id;

            $historyLog = new HistoryLog();
            $historyLog->log = $log;
            $historyLog->causer_id = $causer_id;
            $historyLog->subject_type = 'orders';
            $historyLog->subject_id = $orderId;

            $historyLog->save();

            return response($order, Response::HTTP_NO_CONTENT);
        }

        return response([
            'message' => 'Order or note information not found'
        ], Response::HTTP_NOT_FOUND);

    }

    public function storeDate(Request $request, int $orderId) : Response
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->startOfDay();
            $dataType = $request['type'];

            Log::info('Received data for updating:', [
                'date' => $date,
                'type' => $dataType,
                'orderId' => $orderId
            ]);
            $order = Order::find($orderId);

            if ($order) {

                $result = $this->updateFieldByPath($order, $dataType, $date);

                if ($result) {

                    $causer = User::getUserLogged();
                    $subjectType = $result['subjectType'];
                    $camelCaseSubjectType = lcfirst(str_replace('_', '', ucwords($subjectType, '_'))).'s';

                    $log = json_encode([
                        'causer_name' => $causer->name,
                        'id' => $result['modelInstance']->id,
                        $dataType => $date
                    ]);

                    $historyLog = new HistoryLog();
                    $historyLog->log = $log;
                    $historyLog->causer_id = $causer->id;
                    $historyLog->subject_type = $camelCaseSubjectType;
                    $historyLog->subject_id = $result['modelInstance']->id;

                    $historyLog->save();

                    return response($order, Response::HTTP_NO_CONTENT);
                }

                return response([
                    'message' => 'Related model not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response([
                'message' => 'Order not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error storing date:', ['exception' => $e->getMessage()]);
            return response([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    protected function updateFieldByPath($modelInstance, $fieldPath, $value)
    {
        $fieldComponents = explode('.', $fieldPath);
        
        $current = $modelInstance;

        for ($i = 0; $i < count($fieldComponents) - 1; $i++) {
            $relation = $fieldComponents[$i];

            if (method_exists($current, $relation)) {
                $current = $current->$relation()->first();
                
                if (!$current) {
                    return null;
                }
            } else {
                return null;
            }
        }
        
        $fieldName = end($fieldComponents);
        $current->$fieldName = $value;

        $current->save();
        return ['modelInstance' => $current, 'subjectType' => class_basename($current)];
    }


    public function storeDhl(Request $request, int $orderId) : Response
    {
        $dhl = $request->input('dhl');
        
        $order = Order::find($orderId);

        if ($order) {
            $causer = User::getUserLogged();

            $order->dhl_tracking_number = $dhl;
            $order->save();

            $log = json_encode([
                'causer_name' => $causer->name,
                'id' => $orderId,
                'dhl_tracking_number' => $dhl
            ]);

            $causer_id = User::getUserLogged()->id;

            $historyLog = new HistoryLog();
            $historyLog->log = $log;
            $historyLog->causer_id = $causer_id;
            $historyLog->subject_type = 'orders';
            $historyLog->subject_id = $orderId;

            $historyLog->save();

            return response($order, Response::HTTP_NO_CONTENT);
        }

        return response([
            'message' => 'Order or shipping information not found'
        ], Response::HTTP_NOT_FOUND);

    }

    public function updateContainers(Request $request, int $containerId) : Response
    {

        $data = [
            'name' => $request['name'],
            'tare' => $request['tare'],
            'seal' => $request['seal'],
            'cbm' => $request['cbm'],
            'withdrawal_date' => $request['withdrawal_date'],
            'return_date' => $request['return_date'],
        ];

        $container = Container::whereId($containerId)
            ->update($data);

        return response($container, Response::HTTP_NO_CONTENT);
    }

    public function getCalendarEvents(Request $request)
    {
        $status = $request->input('status') ?? [];
        $loadingLocation = $request->input('loading_location') ?? [];

        $orders = Order::with(['loadings.vehicles.drivers'])

        ->whereHas('loadings', function ($query) {
            return $query->whereHas('vehicles', function ($query) {
                return $query->whereHas('drivers');
            });
        })
       
        ->has('owner')
        ->orderBy('created_at', 'DESC')

        ->get();

        $events = [];
    
        foreach ($orders as $order) {
            $loading = $order->loadings;
            
            if ($loading) {
                foreach ($loading->vehicles as $vehicle) {

                    $statusCondition = !in_array($vehicle->status, $status) && !(is_null($vehicle->status) && in_array('not set', $status));
                    $loadingLocationCondition = !in_array($vehicle->loading_location, $loadingLocation) && !(is_null($vehicle->loading_location) && in_array('not set', $loadingLocation));
                    
                    if ($request->has('status') && $statusCondition || $request->has('loading_location') && $loadingLocationCondition) {
                        continue;
                    }

                    $driver_name = $vehicle->drivers->name;

                    $events[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->number,
                        'driver' => $driver_name,
                        'id' => $vehicle->id,
                        'status' => $vehicle->status,
                        'weight' => $vehicle->net_gross,
                        'plate' => $vehicle->plate,
                        'loading_location' => $vehicle->loading_location,
                        'truck_unloading_datetime' => $vehicle->truck_unloading_datetime,
                        'net_gross' => $vehicle->net_gross
                    ];
                }
            }
        }

        return response()->json(['events' => $events]);
    }
    


}