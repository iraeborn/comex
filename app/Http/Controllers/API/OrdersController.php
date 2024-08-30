<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use App\Service;
use Carbon\Carbon;
use App\Helpers\Utilidade;
use App\Http\Controllers\GenerateDocumentsController;
use App\Item;
use App\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;
use App\Http\Libs\Pdf;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{

    protected $model;
    protected $expensesBrl;
    protected $expensesUsd;
    protected $expensesFob;
    protected $pdf;
    protected $generateDocumentsController;

    public function __construct(Order $model, Pdf $pdf, GenerateDocumentsController $generateDocumentsController)
    {
        $this->model = $model;
        $this->pdf = $pdf;
        $this->generateDocumentsController = $generateDocumentsController;
    }

    private function dreData(Collection $filters)
    {
        $user = \App\User::getUserLogged();
        $query = $this->model
            ->with([
                'container_stuffing',
                'contract_provisions.provision_posting'
            ])
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', $user->group->id)
                    ->orWhere('group_id', $user->group->id)
                    ->orWhere('user_id', $user->id);
            });

        // add filters
        if ($filters->has('order_number')) {
            $query->where('number', '=', $filters->get('order_number'));
        }

        if ($filters->has('start_date')) {
            $query->whereHas('shipping', function ($query) use ($filters) {
                $query->where('loading_deadline', '>=', $filters->get('start_date'));
            });
        }

        if ($filters->has('end_date')) {
            $query->whereHas('shipping', function ($query) use ($filters) {
                $query->where('loading_deadline', '<=', $filters->get('end_date'));
            });
        }

        $rawRecords = $query->get();

        return $rawRecords->map(function ($order) {


            $expenses = round($order->contract_provisions->sum(function ($provision) {
                return $provision->provision_posting->sum(function ($posting) use ($provision) {
                    if ($posting->currency_type == 'BRL' && $provision->status == 2) {
                        return $posting->invoice_amount_converted;
                    }
                    return 0;
                });
            }), 2);

            $expenses_usd = round($order->contract_provisions->sum(function ($provision) {
                return $provision->provision_posting->sum(function ($posting) use ($provision) {
                    if ($posting->currency_type == 'USD' && $provision->status == 2) {
                        return $posting->invoice_amount_converted;
                    }
                    return 0;
                });
            }), 2);

            $expensesFob = round($order->contract_provisions->sum(function ($provision) {
                return $provision->provision_posting->sum(function ($posting) use ($provision) {
                    if ($provision->status == 2) {
                        return $posting->invoice_amount;
                    }
                    return 0;
                });
            }), 2);

            $totalContainers = 0;
            $totalTons = 0;
            $expensePerTon = $expenses;
            $expensePerTonFob = $expensesFob;

            if ($order->container_stuffing) {
                $totalContainers = $order->container_stuffing->qtd_container;
                $totalTons = (float) $order->container_stuffing->quantity_total;

                if ($totalTons > 0) {
                    $expensePerTon = round(($expenses / $totalTons) * 1000, 2);
                }

                if ($totalTons > 0) {
                    $expensePerTonFob = round(($expensesFob / $totalTons) * 1000, 2);
                }
            }

            $record = new stdClass();
            $record->po = $order->number;
            $record->qty_ton = $totalTons;
            $record->qty_container = $totalContainers;
            $record->total_expenses = $expenses;
            $record->total_expenses_usd = $expenses_usd;
            $record->expense_per_ton = $expensePerTon;
            $record->expense_per_ton_fob = $expensePerTonFob;
            $record->total_expenses_fob = $expensesFob;
            $record->shipping = $order->shipping()->first();

            return $record;
        });
    }

    private function dreDataFull(Collection $filters)
    {
        $user = \App\User::getUserLogged();

        $query = $this->model
            ->with([
                'container_stuffing',
                'contract_provisions.provision_posting'
            ])
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', $user->group->id)
                    ->orWhere('group_id', $user->group->id)
                    ->orWhere('user_id', $user->id);
            });

        // add filters
        if ($filters->has('order_number')) {
            $query->where('number', '=', $filters->get('order_number'));
        }

        if ($filters->has('start_date')) {
            $query->whereHas('shipping', function ($query) use ($filters) {
                $query->where('loading_deadline', '>=', $filters->get('start_date'));
            });
        }

        if ($filters->has('end_date')) {
            $query->whereHas('shipping', function ($query) use ($filters) {
                $query->where('loading_deadline', '<=', $filters->get('end_date'));
            });
        }
        $rawRecords = $query->get();

        return $rawRecords->map(function ($order) {

            $this->expensesBrl = [];
            $this->expensesUsd = [];
            $this->expensesFob = [];

            $totalContainers = 0;
            $totalTons = 0;

            if ($order->container_stuffing) {
                $totalContainers = $order->container_stuffing->qtd_container;
                $totalTons = (float) $order->container_stuffing->quantity_total;
            }

            $order->contract_provisions->map(function ($contract_provision) use ($order, $totalContainers, $totalTons) {

                if ($contract_provision->provider_contract->service_id) {

                    $id = 'service' . $contract_provision->provider_contract->service_id;

                    //SOMAR VALORES POR TIPO
                    $this->expensesBrl[$id] = round($order->contract_provisions->sum(function ($provision) use ($contract_provision) {
                        return $provision->provision_posting->sum(function ($posting) use ($provision, $contract_provision) {

                            if (
                                $posting->currency_type == 'BRL' &&
                                $provision->status == 2 &&
                                $provision->provider_contract->service_id == $contract_provision->provider_contract->service_id
                            ) {
                                return $posting->invoice_amount_converted;
                            }
                            return 0;
                        });
                    }), 2);

                    $this->expensesUsd[$id] = round($order->contract_provisions->sum(function ($provision) use ($contract_provision) {
                        return $provision->provision_posting->sum(function ($posting) use ($provision, $contract_provision) {
                            if (
                                $posting->currency_type == 'USD' &&
                                $provision->status == 2 &&
                                $provision->provider_contract->service_id == $contract_provision->provider_contract->service_id
                            ) {
                                return $posting->invoice_amount_converted;
                            }
                            return 0;
                        });
                    }), 2);

                    $this->expensesFob[$id] = round($order->contract_provisions->sum(function ($provision) use ($contract_provision) {
                        return $provision->provision_posting->sum(function ($posting) use ($provision, $contract_provision) {

                            if (
                                $provision->status == 2
                            ) {
                                return $posting->invoice_amount;
                            }
                            return 0;
                        });
                    }), 2);
                }
            });


            $record = new stdClass();
            $record->po = $order->number;
            $record->qty_ton = $totalTons;
            $record->qty_container = $totalContainers;
            $record->expensesBrl = $this->expensesBrl;
            $record->expensesUsd = $this->expensesUsd;
            $record->expensesFob = $this->expensesFob;
            $record->shipping = $order->shipping()->first();

            return $record;
        });
    }

    private function createFilters(HttpRequest $request)
    {

        $filters = collect();

        if ($request->has('order_number')) {
            $filters->put('order_number', $request->get('order_number'));
        }

        if ($request->has('start_date')) {
            $filters->put('start_date', $request->get('start_date'));
        }

        if ($request->has('end_date')) {
            $filters->put('end_date', $request->get('end_date'));
        }

        return $filters;
    }

    public function dreReport(HttpRequest $request)
    {

        $filters = $this->createFilters($request);
        return $this->dreData($filters);
    }

    public function dreReportPDF(HttpRequest $request)
    {

        $filters = $this->createFilters($request);
        $records = $this->dreDataFull($filters);

        if ($records->count() == 0) {
            $data = [
                "po" => "",
                "qty_ton" => 0,
                "qty_container" => 0,
                "total_expenses" => 0,
                "expense_per_ton" => 0,
                "expense_per_ton_fob" => 0,
                "total_expenses_fob" => 0
            ];

            $records = collect([])->push($data);
        }

        $services = Service::where('active', 1)->orderBy('name')->get();
        return view('documents.dre_full', ['data' => $records, 'services' => $services]);
    }

    public function generateDraftBill($id)
    {

        $order = Order::with([
            'consignee',
            'container_stuffing',
            'items',
            'loadings',
            'mapa',
            'owner',
            'shipping',
            'bills'
        ])->find($id);

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.draft_bill',
            'draft_bill',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generateLoadUnitization($id)
    {

        $order = Order::with([
            'consignee',
            'container_stuffing',
            'items',
            'loadings',
            'mapa',
            'owner',
            'shipping'
        ])->find($id);

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.load_unitization_draft',
            'load_unitization_draft',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generateCommercialInvoice($id)
    {

        $order = Order::with([
            'consignee',
            'container_stuffing',
            'items',
            'loadings',
            'mapa',
            'owner',
            'shipping',
            'transactions' => function ($query) {
                $query->where('transaction_type_id', '=', 2);
            }
        ])->find($id);

        $data = $order;

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.commercial_invoice',
            'commercial_invoice',
            $data
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generatePackingList($id)
    {

        $order = Order::with([
            'consignee',
            'container_stuffing',
            'items',
            'loadings',
            'mapa',
            'owner',
            'shipping',
            'notify.contacts' => function ($query) {
                $query->where('contact_type_id', '=', 2)
                    ->orWhere('contact_type_id', '=', 4);
            },
            'owner.contacts' => function ($query) {
                $query->where('contact_type_id', '=', 2)
                    ->orWhere('contact_type_id', '=', 4);
            },
            'consignee.contacts' => function ($query) {
                $query->where('contact_type_id', '=', 2)
                    ->orWhere('contact_type_id', '=', 4);
            }
        ])->find($id);

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.packing_list',
            'packing_list',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generateFumigationDeclaration($id)
    {

        $order = Order::find($id);

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.fumigation_declaration',
            'fumigation_declaration',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generatePhytoCertificate($id)
    {

        $order = Order::find($id);

        // $data = $order;
        // return view('documents.stuffing_loading.packing_list', compact('data'));
        $url = $this->pdf->getFile(
            'documents.stuffing_loading.phyto_certificate',
            'phyto_certificate',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    public function generateBLDraft($orderId)
    {

        $order = Order::find($orderId);

        $url = $this->pdf->getFile(
            'documents.stuffing_loading.bl_draft',
            'bl_draft',
            $order
        );

        $file = Storage::download($url);
        return $file;
    }

    private function filterOrders($query, $filter)
    {

        $user = \App\User::getUserLogged();

        return $query
            ->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->where(function ($query) use ($filter) {
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
                    });
            });
    }

    public function pendingOrdersReportPDF(HttpRequest $request)
    {
        $user = User::getUserLogged();
        $initial_date = $request->input('initial_date');
        $final_date = $request->input('final_date');

        $orders = Order::where('group_id', $user->group_id)
            ->with('items')
            ->has('owner')
            ->where(function ($q) use ($user) {
                return $q->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })

            ->where(function ($query) {
                return $query->where('order_status_id', 1)
                    ->orWhereHas('shipping', function ($query) {
                        return $query->whereNull('booking');
                    })
                    ->orDoesntHave('shipping');
            })

            ->when(isset($initial_date) && isset($final_date), function ($query) use ($initial_date, $final_date) {
                return $query->whereBetween('created_at', [$initial_date, $final_date]);
            })

            ->orderByDesc('created_at')->get();

        $url = $this->pdf->getFile(
            'documents.pending_orders',
            'pending_orders',
            $orders
        );

        $file = Storage::download($url);
        return $file;
    }

    private function filterTruckLoading($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            return $query->where('number', 'like', '%' . $filter . '%')
                ->orWhereHas('owner', function ($query) use ($filter) {
                    return $query->where('name', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('items', function ($query) use ($filter) {
                    return $query->where('description', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('container_stuffing', function ($query) use ($filter) {
                    return $query->where('terminal', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('loadings', function ($query) use ($filter) {
                    return $query->where('loading_location', 'like', '%' . $filter . '%');
                });
        });
    }

    public function truckLoadingReportPDF(HttpRequest $request)
    {
        $orders = Order::with([
            'owner',
            'items',
            'loadings',
            'specifications.specification',
            'loadings.vehicles',
        ])
            ->has('loadings')
            ->whereHas('shipping', function ($query) {
                return $query->whereNotNull('booking')
                    ->where('etd', '>=', Carbon::now());
            })
            ->when($request->has('initial_date') && $request->input('initial_date'), function ($query) use ($request) {
                return $query->whereHas('container_stuffing', function ($query) use ($request) {
                    return $query->where('stuffing_starting_date', '>=', $request->input('initial_date'));
                });
            })
            ->when($request->has('final_date') && $request->input('final_date'), function ($query) use ($request) {
                return $query->whereHas('container_stuffing', function ($query) use ($request) {
                    return $query->where('stuffing_starting_date', '<=', $request->input('final_date'));
                });
            })
            ->when($request->has('filters'), function ($query) use ($request) {
                if (is_array($request->input('filters'))) {
                    foreach ($request->input('filters') as $filter) {
                        $this->filterTruckLoading($query, $filter);
                    }
                } elseif ($request->input('filters')) {
                    $this->filterTruckLoading($query, $request->input('filters'));
                }
            })
            ->get();

        $orderDetails = [];

        foreach ($orders as $order) {
            $order['total_shipped'] = 0;
            $order['number'];
            $order['items'];

            $order['balance'] = 0;

            $order['stuffing_starting_date'] = Carbon::parse($order['container_stuffing']['stuffing_starting_date'])->format('d/m/Y');

            if ($order['loadings']) {
                foreach ($order['loadings']['vehicles'] as $vehicle) {
                    $order['total_shipped'] += $vehicle['wheight'];
                }

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
        }

        $orderDetails = collect($orderDetails);

        $url = $this->pdf->getFile(
            'documents.truck_loading',
            'truck_loading',
            $orderDetails,
            'landscape'
        );

        $file = Storage::download($url);
        return $file;
    }

    private function filterReceivableOrders($query, $filter)
    {
        return $query->where(function ($query) use ($filter) {
            return $query->where('number', 'like', '%' . $filter . '%')
                ->orWhere('nf', 'like', '%' . $filter . '%')
                ->orWhereHas('items', function ($query) use ($filter) {
                    return $query->where('description', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('owner', function ($query) use ($filter) {
                    return $query->where('name', 'like', '%' . $filter . '%');
                })
                ->orWhereHas('shipping', function ($query) use ($filter) {
                    return $query->where('booking', 'like', '%' . $filter . '%');
                });
        });
    }

    public function receivableOrdersReportPDF(HttpRequest $request)
    {
        $user = User::getUserLogged();

        $orders = Order::when($request->has('filters'), function ($query) use ($request) {
            if (is_array($request->input('filters'))) {
                foreach (array_filter($request->input('filters')) as $filter) {
                    $this->filterReceivableOrders($query, $filter);
                }
            } elseif ($request->input('filters')) {
                $this->filterReceivableOrders($query, $request->input('filters'));
            }
        })
            ->when($request->input('initial_date'), function ($query) use ($request) {
                return $query->where('expiry_date', '>', $request->input('initial_date'));
            })
            ->when($request->input('final_date'), function ($query) use ($request) {
                return $query->where('expiry_date', '<', $request->input('final_date'));
            })

            ->where(function ($q) use ($user) {
                $q->whereRaw('? = 1', [$user->group->id])
                    ->orWhere('group_id', $user->group->id);
            })
            ->where('group_id', $user->group->id)

            ->orderBy('expiry_date')->get();

        $orders->each(function ($order, $key) {
            $order->shipping = $order->shipping()->first();
            $order->transactions = $order->transactions()->first();
            $order->owner = $order->owner()->first();
            $order->items = $order->items()->get();
            $order->mapa = $order->mapa()->first();
            $order->sum = 0;
            $order->sum2 = 0;

            $transactions = $order->transactions()->get();

            foreach ($transactions as $transaction) {
                if ($transaction->transaction_type()->first()->display_to_importer) {
                    $order->sum += $transaction->getOriginal('value');
                }
                if ($transaction->transaction_type()->first()->id != 1) {
                    if ($transaction->transaction_type()->first()->id == 2 || $transaction->transaction_type()->first()->id == 5)
                        $order->sum2 += $transaction->getOriginal('value');
                    else
                        $order->sum2 -= $transaction->getOriginal('value');
                }
            }


            $order->status_payment = \App\Transaction::getStatus($order, $order->sum);

            if (!$order->expiry_date) {
                $order->expiry_date = '0000-00-00 00:00:00';
            }

            if ($order->expiry_date != '0000-00-00 00:00:00' && empty($order->shipping) && $order->shipping['eta'] == 0) {
                $order->expiry_date = $this->AddDaysAndFormat($order->expiry_date, 0);
            } else {
                $order->expiry_date = $this->AddDaysAndFormat($order->shipping['eta'], 14);
            }

            return true;
        });

        $orders = $orders->filter(function ($order) {
            return isset($order->shipping->booking);
        });

        $orders = $orders->filter(function ($order, $key) {
            return !!$order->transactions;
        });

        $orders = $orders->filter(function ($order) {
            return !!$order->sum;
        });

        $orders = $orders->filter(function ($order) {
            return abs($order->sum) / max(abs($order->sum), 1) > 1e-10;
        });

        $orders = $orders->map(function ($order) {
            $date = new Carbon($order->expiry_date);
            $order->month = $date->localeMonth . '/' . $date->year;

            return $order;
        });

        $months = $orders->pluck('month')->unique();

        // $sortedMonths = $months->sortByAsc(function ($month) {
        //     return Carbon::createFromFormat('F/Y', $month);
        // });

        $sortedMonths = $months->sortBy(function ($month) {
            return Carbon::createFromFormat('F/Y', $month);
        });
        
        $data = [
            'orders' => $orders,
            'months' => $sortedMonths->values()->all()
        ];

        $url = $this->pdf->getFile(
            'documents.receivable_orders',
            'pending_orders',
            $data,
            'landscape'
        );

        $file = Storage::download($url);

        return $file;
    }

    private function AddDaysAndFormat($date, $plusDays = 0) {
        $date = new DateTime($date);
        
        if ($plusDays) {
            $date->modify("+$plusDays days");
        }
        
        return $date->format('Y-m-d H:i:s');
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
                                    });;
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

    public function reportsReportPDF(HttpRequest $request)
    {
        $status = $request->input('status');

        $orders = \App\Order::with(['owner', 'items', 'shipping', 'loadings.vehicles.drivers', 'loadings.vehicles.carriers', 'container_stuffing', 'mapa'])
            ->has('owner')
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

        $data = [
            'status' => $status,
            'orders' => $orders
        ];

        $url = $this->pdf->getFile(
            'documents.reports',
            'report-' . $status,
            $data,
            'landscape'
        );

        $file = Storage::download($url);
        return $file;
    }

    public function provisionsReportPDF(HttpRequest $request)
    {        
        $provisions = \App\ContractProvision::with([
            'provider_contract.provider',
            'provider_contract.contract_services',
            'order.items',
            'order.shipping',
            'order.mapa'
        ])
            ->has('order')
            ->when($request->has('filter') && $request->input('filter'), function ($query) use ($request) {
                $query->when($request->input('provider'), function ($query) use ($request) {
                    return $query->where('status', 1)->whereHas('provider_contract', function ($query) use ($request) {
                        return $query->where('provider_id', $request->input('provider'));
                    });
                })->when($request->input('service_type'), function ($query) use ($request) {
                    return $query->where('status', 1)->whereHas('provider_contract', function ($query) use ($request) {
                        return $query->where('service_type', 'like', '%' . $request->input('service_type') . '%');
                    });
                })->when($request->input('order_number'), function ($query) use ($request) {
                    return $query->whereHas('order', function ($query) use ($request) {
                        return $query->where('number', 'like', '%' . $request->input('order_number') . '%');
                    });
                })->when($request->input('booking'), function ($query) use ($request) {
                    return $query->whereHas('order', function ($query) use ($request) {
                        return $query->whereHas('shipping', function ($query) use ($request) {
                            return $query->where('booking', 'like', '%' . $request->input('booking') . '%');
                        });
                    });
                });
            }, function ($query) {
                return $query->where('status', 1);
            })
            ->get();

        $provisionWithValues = $provisions->map(function ($provision) {
            $provision = $this->calculateProvisionValues($provision);
            return $provision;
        });

        $url = $this->pdf->getFile(
            'documents.provisions',
            'relatorio-provisions',
            $provisionWithValues,
            'landscape'
        );

        $file = Storage::download($url);
        return $file;
    }

    private function calculateProvisionValues($provision)
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
                if (($service->billing_factor_type == 1) or ($service->billing_factor_type == 2)) {
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

        return $provision;
    }

    public function invoicesReportPDF(HttpRequest $request)
    {
        $rawRecords = \App\ProvisionPosting::with([
            'contract_provision.provider_contract.provider',
            'contract_provision.order.shipping',
            'contract_provision.order.mapa'
        ])
            ->get();

        $records = $rawRecords->map(function ($posting) {

            $supplier = '';
            try {
                if ($posting->contract_provision && $posting->contract_provision->provider_contract && $posting->contract_provision->provider_contract->provider) {
                    $supplier = $posting
                        ->contract_provision
                        ->provider_contract
                        ->provider
                        ->name;
                }
            } catch (Exception $exception) {
                Log::error($exception);
                $supplier = '';
            }

            $booking = '';
            try {
                if ($posting->contract_provision && $posting->contract_provision->order && $posting->contract_provision->order->shipping()->first()) {
                    $booking = $posting->contract_provision->order->shipping->first()->booking;
                }
            } catch (Exception $exception) {
                Log::error($exception);
                $booking = '';
            }

            $nfe = '';
            try {
                if ($posting->contract_provision && $posting->contract_provision->order && $posting->contract_provision->order->mapa) {
                    $nfe = $posting->contract_provision->order->mapa->nfe_key;
                }
            } catch (Exception $exception) {
                Log::error($exception);
                $nfe = '';
            }

            $po = '';
            try {
                if ($posting->contract_provision && $posting->contract_provision->order) {
                    $po = $posting->contract_provision->order->number;
                }
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


        function check($haystack, $needle)
        {
            $haystack = strtolower($haystack);
            $needle = strtolower($needle);

            if ($haystack && $needle) {
                return substr_count($haystack, $needle);
            } else {
                return false;
            }
        }

        if ($request->has('order') && $request->input('order')) {
            $records = $records->filter(function ($record) use ($request) {
                return check($record->order_po, $request->input('order'));
            });
        }
        if ($request->has('booking') && $request->input('booking')) {
            $records = $records->filter(function ($record) use ($request) {
                return check($record->order_booking, $request->input('booking'));
            });
        }
        if ($request->has('nfe') && $request->input('nfe')) {
            $records = $records->filter(function ($record) use ($request) {
                return check($record->order_nfe, $request->input('nfe'));
            });
        }
        if ($request->has('provider') && $request->input('provider')) {
            $records = $records->filter(function ($record) use ($request) {
                return check($record->supplier_name, $request->input('provider'));
            });
        }
        if ($request->has('invoice') && $request->input('invoice')) {
            $records = $records->filter(function ($record) use ($request) {
                return check($record->invoice_id, $request->input('invoice'));
            });
        }

        if ($request->has('initial_date') && $request->input('initial_date')) {
            $records = $records->filter(function ($record) use ($request) {
                return (new Carbon($record->invoice_due_date))->greaterThanOrEqualTo(new Carbon($request->input('initial_date')));
            });
        }
        if ($request->has('final_date') && $request->input('final_date')) {
            $records = $records->filter(function ($record) use ($request) {
                return (new Carbon($record->invoice_due_date))->lessThanOrEqualTo(new Carbon($request->input('final_date')));
            });
        }

        // $url = $this->pdf->getFile(
        //     'documents.invoices',
        //     'relatorio-invoices',
        //     $records,
        //     'landscape'
        // );

        // $file = Storage::download($url);

        $invoicePrefixNameFile = 'invoice_' . $records->id . '_' . date("dmY-His");
        $invoiceUrl = $this->generateDocumentsController->invoice($records, $invoicePrefixNameFile);

        return $invoiceUrl;
    }

    public function ordersLastFewMonths()
    {
        $user = User::getUserLogged();

        $orders = Order::with('shipping')
            ->leftJoin('shippings', 'orders.id', '=', 'shippings.order_id')
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(6))
            ->where('orders.group_id', $user->group->id)
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->created_at)->format('Y-m');
            });

        $months = [];
        $ordersCount = [];
        $containersCount = [];

        foreach ($orders as $key => $orderGroup) {
            $monthYear = Carbon::parse("$key-01")->translatedFormat('F Y');
            $months[] = $monthYear;
            $ordersCount[] = $orderGroup->count();

            $totalContainers = 0;

            foreach ($orderGroup as $order) {
                if ($order->shipping->isNotEmpty()) {
                    foreach ($order->shipping as $shipping) {
                        $totalContainers += $shipping->quantity_containers;
                    }
                }
            }

            $containersCount[] = $totalContainers;
        }

        return response()->json([
            'months' => $months,
            'orders_count' => $ordersCount,
            'containers_count' => $containersCount,
        ]);
    }



    public function itemsLastFewMonths()
    {
        $user = User::getUserLogged();
        $items = Item::select('description', 'quantity')
            ->whereIn(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), function ($query) {
                $query->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
                    ->from('items')
                    ->where('created_at', '>=', Carbon::now()->subMonths(1));
            })
            ->where('quantity', '>', 0)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        $itemsGroupedByDescription = $items->groupBy('description');

        $itemsData = [];

        foreach ($itemsGroupedByDescription as $description => $groupedItems) {
            $totalQuantity = $groupedItems->sum('quantity');

            $itemsData[] = [
                'description' => $description,
                'total_quantity' => $totalQuantity,
            ];
        }

        return response()->json([
            'items_data' => $itemsData,
        ]);
    }
}
