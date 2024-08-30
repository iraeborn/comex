<?php

namespace App\Http\Controllers;

use App\Actions\Transactions\NewTransactionAction;
use App\ForeignAccountPosting;
use App\Http\Requests\ProvisionPostingRequest;
use App\ProvisionPosting;
use App\Transaction;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Libs\Pdf;
use App\TransactionType;
use Carbon\Carbon;

class TransactionApiController extends Controller
{
    protected $newTransactionAction;
    protected $pdf;

    public function __construct(NewTransactionAction $newTransactionAction, Pdf $pdf)
    {
        $this->newTransactionAction = $newTransactionAction;
        $this->pdf = $pdf;
    }

    public function index(Request $request)
{
    $user = User::getUserLogged();
    
    // Define as variáveis de status
    $foreign = $request->has('status') && $request->input('status') == 'foreign';
    $toboard = $request->has('status') && $request->input('status') == 'toboard';
    $available = $request->has('status') && $request->input('status') == 'available';

    // Eager loading das relações necessárias
    $orders = Order::query()
        ->with([
            'shipping',
            'transactions.transaction_type',
            'owner',
            'items',
            'mapa'
        ])
        ->where(function ($q) use ($user) {
            $q->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
        ->where('group_id', $user->group->id)
        ->when($toboard, function ($q) {
            return $q->where(function ($query) {
                $query->where('order_status_id', 1)
                    ->orWhereHas('shipping', function ($query) {
                        $query->whereNull('booking');
                    })
                    ->orDoesntHave('shipping');
            });
        })
        ->when($user->roles->first()->name == "Exporter", function ($query) use ($user) {
            return $query->where('exporter_id', $user->id);
        })
        ->orderBy('id', 'desc')
        ->get();

    // Trabalhando apenas com os pedidos necessários para a condição 'available'
    if ($available) {
        $orders = DB::table('orders')
            ->join('transactions', 'transactions.order_id', '=', 'orders.id')
            ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
            ->join('shippings', 'shippings.order_id', '=', 'orders.id')
            ->join('users', 'users.id', '=', 'orders.owner_id')
            ->where('transactions.transaction_type_id', 8)
            ->where('orders.group_id', $user->group->id)
            ->select('transactions.*', 'orders.*', 'transaction_types.*', 'shippings.*', 'users.name', 'users.email', 'users.username', 'users.country')
            ->groupBy('orders.id')
            ->orderBy('orders.id', 'desc')
            ->get();

        $totalOrdersValue = $orders->sum('value');

        $operations = [
            'transactions' => array_values($orders->toArray()),
            'postings' => ForeignAccountPosting::with('supplier')->orderBy('due_date')->orderBy('created_at')->get(),
            'totalOrdersValue' => $totalOrdersValue,
        ];

        return response()->json($operations);
    }

    // Calculando as somas e filtrando transações em lote, fora do loop
    $orders->each(function ($order) use ($foreign, $toboard, $available, $request) {
        $order->sum = 0;
        $order->sum2 = 0;
        $order->sum3 = 0;

        $transactions = $order->transactions; // Recupera as transações relacionadas

        foreach ($transactions as $transaction) {
            $transactionType = $transaction->transaction_type;
            
            if ($transactionType->display_to_importer || $transactionType->id == 9) {
                $order->sum += $transaction->getOriginal('value');
            } else if ($transactionType->id == 8) {
                $order->sum -= $transaction->getOriginal('value');
            }

            if ($toboard) {
                $order->sum3 = $transaction->getOriginal('value');
            } else if ($available) {
                $transaction->order = $order;
            } else {
                if ($transactionType->id != 1 && $transactionType->id != 9 && !$foreign) {
                    if (in_array($transactionType->id, [2, 5, 8])) {
                        $order->sum2 += $transaction->getOriginal('value');
                    } else {
                        $order->sum2 -= $transaction->getOriginal('value');
                    }
                } else if ($transactionType->id == 7 && $foreign) {
                    $order->sum2 += $transaction->getOriginal('value');
                }
            }
        }

        $order->transactions = $transactions->toArray();

        $order->status_payment = Transaction::getStatus($order, $order->sum);

        if (!$order->expiry_date) {
            $order->expiry_date = '0000-00-00 00:00:00';
        }
    });

    $orders = $orders->filter(function ($order) {
        return !!$order->transactions;
    });

    return response()->json(array_values($orders->toArray()));
}


    // public function index(Request $request)
    // {
    //     $user = User::getUserLogged();
        
    //     $foreign = $request->has('status') && $request->input('status') == 'foreign';
    //     $toboard = $request->has('status') && $request->input('status') == 'toboard';
    //     $available = $request->has('status') && $request->input('status') == 'available';

    //     $orders = Order::query()
    //         ->with([
    //             'shipping',
    //             'transactions.transaction_type',
    //             'owner',
    //             'items',
    //             'mapa'
    //         ])
    //         ->where(function ($q) use ($user) {
    //             $q->whereRaw('? = 1', [$user->group->id])
    //                 ->orWhere('group_id', $user->group->id);
    //         })
    //         ->where('group_id', $user->group->id)
    //         ->when($toboard, function ($q) {
    //             return $q->where(function ($query) {
    //                 $query->where('order_status_id', 1)
    //                     ->orWhereHas('shipping', function ($query) {
    //                         $query->whereNull('booking');
    //                     })
    //                     ->orDoesntHave('shipping');
    //             });
    //         })
    //         ->when($user->roles->first()->name == "Exporter", function ($query) use ($user) {
    //             return $query->where('exporter_id', $user->id);
    //         })
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     if ($available) {
    //         $orders = DB::table('orders')
    //             ->join('transactions', 'transactions.order_id', '=', 'orders.id')
    //             ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
    //             ->join('shippings', 'shippings.order_id', '=', 'orders.id')
    //             ->join('users', 'users.id', '=', 'orders.owner_id')
    //             ->where('transactions.transaction_type_id', 8)
    //             ->where('orders.group_id', $user->group->id)
    //             ->select('transactions.*', 'orders.*', 'transaction_types.*', 'shippings.*', 'users.name', 'users.email', 'users.username', 'users.country')
    //             ->groupBy('orders.id')
    //             ->orderBy('orders.id', 'desc')
    //             ->get();

    //         $totalOrdersValue = $orders->sum('value');

    //         $operations = [
    //             'transactions' => array_values($orders->toArray()),
    //             'postings' => ForeignAccountPosting::with('supplier')->orderBy('due_date')->orderBy('created_at')->get(),
    //             'totalOrdersValue' => $totalOrdersValue,
    //         ];

    //         return response()->json($operations);
    //     }

    //     $orders->each(function ($order) use ($foreign, $toboard, $available, $request) {
    //         $order->sum = 0;
    //         $order->sum2 = 0;
    //         $order->sum3 = 0;

    //         foreach ($order->transactions as $transaction) {
    //             $transactionType = $transaction->transaction_type;
                
    //             if ($transactionType->display_to_importer || $transactionType->id == 9) {
    //                 $order->sum += $transaction->getOriginal('value');
    //             } else if ($transactionType->id == 8) {
    //                 $order->sum -= $transaction->getOriginal('value');
    //             }

    //             if ($toboard) {
    //                 $order->sum3 = $transaction->getOriginal('value');
    //             } else if ($available) {
    //                 $transaction->order = $order;
    //             } else {
    //                 if ($transactionType->id != 1 && $transactionType->id != 9 && !$foreign) {
    //                     if (in_array($transactionType->id, [2, 5, 8])) {
    //                         $order->sum2 += $transaction->getOriginal('value');
    //                     } else {
    //                         $order->sum2 -= $transaction->getOriginal('value');
    //                     }
    //                 } else if ($transactionType->id == 7 && $foreign) {
    //                     $order->sum2 += $transaction->getOriginal('value');
    //                 }
    //             }
    //         }

    //         $order->status_payment = Transaction::getStatus($order, $order->sum);

    //         if (!$order->expiry_date) {
    //             $order->expiry_date = '0000-00-00 00:00:00';
    //         }
    //     });

    //     $orders = $orders->filter(function ($order) {
    //         return !!$order->transactions;
    //     });

    //     return response()->json($orders);
    // }


    public function index2(Request $request)
    {
        $user = User::getUserLogged();
        $orders = Order::query()->where(function ($q) use ($user) {
            $q->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
            ->where('group_id', $user->group->id)
            ->when($request->input('status') == 'toboard', function ($q) {
                return $q->where(function ($query) {
                    return $query->where('order_status_id', 1)
                        ->orWhereHas('shipping', function ($query) {
                            return $query->whereNull('booking');
                        })
                        ->orDoesntHave('shipping');
                });
            })
            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user) {
                return $query->where('exporter_id', $user->id);
            })
            ->orderBy('id', 'desc')
            ->get();

        $foreign = false;
        $toboard = false;
        $available = false;
        $allTransactions = collect([]);

        if ($request->input('status') == 'available') {

            $orders = DB::table('orders')
                ->join('transactions', 'transactions.order_id', '=', 'orders.id')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->join('shippings', 'shippings.order_id', '=', 'orders.id')
                ->join('users', 'users.id', '=', 'orders.owner_id')
                ->where('transactions.transaction_type_id', 8)
                ->where('orders.group_id', $user->group->id)
                ->select('transactions.*', 'orders.*', 'transaction_types.*', 'shippings.*', 'users.name', 'users.email', 'users.username', 'users.country')
                ->groupBy((array)'orders.id')
                ->orderBy('orders.id', 'desc')
                ->get();

            $totalOrdersValue = $orders->sum('value');

            $operations = [
                'transactions' => array_values($orders->toArray()),
                'postings' => ForeignAccountPosting::with('supplier')->orderBy('due_date')->orderBy('created_at')->get(),
                'totalOrdersValue' => $totalOrdersValue,
            ];

            return response()->json($operations);
        }

        if ($request->has('status') && in_array($request->input('status'), ['foreign'])) {
            $foreign = true;
        }

        if ($request->has('status') && in_array($request->input('status'), ['toboard'])) {
            $toboard = true;
        }

        if ($request->has('status') && in_array($request->input('status'), ['available'])) {
            $available = true;
        }

        $orders->each(function ($order, $key) use ($foreign, $allTransactions, $toboard, $available, $request) {
            $order->shipping = $order->shipping()->first();

            if ($request->input('status') != 'toboard') {
                $order->transactions = $order->transactions()->first();
            } else {
                $order->transactions = $order->transactions();
            }

            $order->owner = $order->owner()->first();
            $order->items = $order->items()->get();
            $order->mapa = $order->mapa()->first();
            $order->sum = 0;
            $order->sum2 = 0;
            $order->sum3 = 0;

            $transactions = $order->transactions()->with('transaction_type')->get();

            foreach ($transactions as $transaction) {
                if ($transaction->transaction_type()->first()->display_to_importer || $transaction->transaction_type()->first()->id == 9) {
                    $value = $transaction->getOriginal('value');
                    $order->sum += $value;
                } else if ($transaction->transaction_type()->first()->id == 8) {
                    $value = $transaction->getOriginal('value');
                    $order->sum -= $value;
                }

                if ($toboard) {
                    $order->sum3 = $transaction->getOriginal('value');
                };

                if ($available) {
                    $transaction->order = $order;
                } else {
                    if ($transaction->transaction_type()->first()->id != 1 && $transaction->transaction_type()->first()->id != 9 && !$foreign) {
                        if ($transaction->transaction_type()->first()->id == 2 || $transaction->transaction_type()->first()->id == 5 || $transaction->transaction_type()->first()->id == 8)
                            $order->sum2 += $transaction->getOriginal('value');
                        else
                            $order->sum2 -= $transaction->getOriginal('value');
                    } else if ($transaction->transaction_type()->first()->id == 7 && $foreign) {
                        $order->sum2 += $transaction->getOriginal('value');
                        $transaction->order->owner;
                        $allTransactions = $allTransactions->push($transaction);
                    }
                }
            }

            $order->status_payment = Transaction::getStatus($order, $order->sum);

            if (!$order->expiry_date) {
                $order->expiry_date = '0000-00-00 00:00:00';
            }

            return true;
        });

        $orders = $orders->filter(function ($order, $key) {
            return !!$order->transactions;
        });

        if ($foreign && $allTransactions) {
            $allTransactions = $allTransactions->sortBy('data')->sortBy('created_at');
        }


        if ($request->has('status')) {

            $status = $request->input('status');

            if ($status == 'toboard') {

                $orders = $orders->filter(function ($order) {
                    return $order;
                });

                return response()->json(array_values($orders->toArray()));
            } else if ($status == 'receivable') {

                $orders = $orders->filter(function ($order) {
                    return !!$order->sum;
                });

                $orders = $orders->filter(function ($order) {
                    return abs($order->sum) / max(abs($order->sum), 1) > 1e-10;
                });

                $orders = $orders->filter(function ($order) {
                    return isset($order->shipping->booking);
                });
            } else if ($status == 'paid') {
                $orders = $orders->filter(function ($order) {
                    return !$order->sum;
                });
                $orders = $orders->filter(function ($order) {
                    return !$order->sum;
                });
            } else if ($status == 'foreign') {
                $operations = [
                    'transactions' => array_values($allTransactions->toArray()),
                    'postings' => ForeignAccountPosting::with('supplier')->orderBy('due_date')->orderBy('created_at')->get()
                ];
                return response()->json($operations);
            } else if ($status == 'available') {
                $operations = [
                    'transactions' => array_values($allTransactions->toArray()),
                    'postings' => ForeignAccountPosting::with('supplier')->orderBy('due_date')->orderBy('created_at')->get()
                ];
                return response()->json($operations);
            }
        }

        return response()->json(array_values($orders->toArray()));
    }

    public function indexClient(HttpRequest $request)
    {
        $user = \App\User::find($request->get('user_id'));

        if ($user->hasRole('Broker')) {
            $orders = $user->brokerOrders()->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', $user->group->id)
                    ->orWhere('group_id', $user->group->id)
                    ->orWhere('user_id', $user->id);
            })->get();
        } else {
            $orders = $user->orders()->where(function ($q) use ($user) {
                $q
                    ->whereRaw('? = 1', $user->group->id)
                    ->orWhere('group_id', $user->group->id)
                    ->orWhere('user_id', $user->id);
            })->get();
        }

        $orders->each(function ($order, $key) {
            $order->shipping = $order->shipping()->first();
            $order->transactions = $order->transactions()->first();
            $order->owner = $order->owner()->first();
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


            $order->status_payment = Transaction::getStatus($order, $order->sum);

            return true;
        });



        $orders = $orders->filter(function ($order, $key) {
            return !!$order->transactions;
        });

        return response()->json($orders);
    }

    public function get($order_id)
    {
        $order = Order::find($order_id);
        $transactions = $order->transactions()->get();
        $transactions->each(function ($transaction, $key) {
            $transaction->transaction_type = $transaction->transaction_type()->first();
            $transaction->owner = $transaction->order()->first()->owner()->first();
            return true;
        });

        return response()->json($transactions);
    }

    public function post(Request $request, $order_id)
    {
        $result = ($this->newTransactionAction)($request, $order_id);
        return response()->json($result);
    }

    public function put(HttpRequest $request, $order_id)
    {
        $value = $request->input('value');

        if ($request->input('transaction_type_id') == 1) {
            $value = -abs($value);
        }
        $transaction = new \App\Transaction();
        if ($request->input('id')) {
            $transaction = \App\Transaction::find($request->input('id'));
        }

        $transaction->value = $value;

        $transaction->transaction_type_id = $request->input('transaction_type_id');

        $transaction->description = $request->input('description');
        $transaction->bank = $request->input('bank');
        $transaction->dollar_value = $request->input('dollar_value');
        $transaction->converted_value = $request->input('converted_value');
        $transaction->cambio_contract = $request->input('cambio_contract');
        $transaction->reference = $request->input('reference');
        $transaction->data = $request->input('data');

        $transaction->order_id = $order_id;

        $save = $transaction->save();

        $transaction->transaction_type = $transaction->transaction_type()->first();
        $transaction->owner = $transaction->order()->first()->owner()->first();

        return response()->json(['success' => $save, 'transaction' => $transaction]);
    }

    public function getTransactionTypeList()
    {
        $transaction_types = \App\TransactionType::all();
        return response()->json($transaction_types);
    }

    public function putTransactionTypeList(TransactionType $transactionType, HttpRequest $request, $id = null)
    {
        $transaction_type = $transactionType::find($id);

        if (!$transaction_type) {
            $transaction_type = new $transactionType();
        }

        $transaction_type->name = $request->input('name');
        $transaction_type->display_to_importer = $request->input('display_to_importer');
        $transaction_type->deletable = 1;
        $transaction_type->save();

        return response()->json($transaction_type);
    }

    public function deleteTransactionTypeList(TransactionType $transactionType, $id = null)
    {
        $transaction_type = $transactionType::find($id);

        if (!$transaction_type->deletable)
            return response()->json(['success' => false]);

        return response()->json(['success' => $transaction_type->delete()]);
    }

    public function storePosting(ProvisionPostingRequest $request)
    {
        $originalInvoiceAmount = $request->get('invoice_amount', 0);
        $invoiceAmount = $request->get('invoice_amount', 0) * 100;
        $request->merge(['invoice_amount' => $invoiceAmount]);

        $fee = $request->get('currency_fee', 1);
        if ($fee < 1) {
            $fee = 1;
        }

        $feeConverted = $fee * 100;
        $request->merge(['currency_fee' => $feeConverted]);

        $amountedConverted = (int) (($originalInvoiceAmount * $fee) * 100);
        $request->merge(['invoice_amount_converted' => $amountedConverted]);

        $posting = ForeignAccountPosting::create($request->all());

        return ForeignAccountPosting::with('supplier')->find($posting->id);
    }

    public function updatePosting(ProvisionPostingRequest $request, int $id)
    {
        $originalInvoiceAmount = $request->get('invoice_amount', 0);
        $invoiceAmount = $request->get('invoice_amount', 0) * 100;
        $request->merge(['invoice_amount' => $invoiceAmount]);

        $fee = $request->get('currency_fee', 1);
        if ($fee < 1) {
            $fee = 1;
        }

        $feeConverted = $fee * 100;
        $request->merge(['currency_fee' => $feeConverted]);


        $amountedConverted = (int) (($originalInvoiceAmount * $fee) * 100);
        $request->merge(['invoice_amount_converted' => $amountedConverted]);

        $posting = ForeignAccountPosting::where('id', '=', $id)
            ->update(
                [
                    'invoice_id' => $request->input('invoice_id'),
                    'description' => $request->input('description'),
                    'invoice_amount' => $request->input('invoice_amount'),
                    'invoice_amount_converted' => $request->input('invoice_amount_converted'),
                    'currency_fee' => $request->input('currency_fee'),
                    'currency_type' => $request->input('currency_type'),
                    'due_date' => $request->input('due_date')
                ]
            );

        $posting = ForeignAccountPosting::find($id);

        if ($posting && $posting->provision_posting_id) {
            $data = [
                'invoice_id' => $request->input('invoice_id'),
                'invoice_amount' => $request->input('invoice_amount'),
                'invoice_amount_converted' => $request->input('invoice_amount_converted'),
                'currency_fee' => $request->input('currency_fee'),
                'currency_type' => $request->input('currency_type'),
                'due_date' => $request->input('due_date')
            ];

            $provision_posting = ProvisionPosting::find($posting->provision_posting_id)->update($data);
        }

        return ForeignAccountPosting::with('supplier')->find($id);
    }

    public function deletePosting(int $id)
    {

        $posting = ForeignAccountPosting::find($id);
        $posting->delete();

        return response()->json(['sucess' => true]);
    }

    public function deleteTransaction(int $order_id, int $id)
    {

        $transaction = \App\Transaction::find($id);
        $transaction->delete();

        return response()->json(['sucess' => true]);
    }

    public function report(Request $request)
    {
        $initial_date = $request->input('initial_date');
        $final_date = $request->input('final_date');
        $importer_id = $request->input('importer_id');
        $transaction_types = $request->input('transaction_types');
        $searchByDate = $request->input('searchByDate');
        $terms = $request->input('terms');

        $user = User::getUserLogged();

        $query = transaction::with(['order', 'order.mapa'])
            ->whereHas('order', function ($query) use ($user) {
                $query->where('group_id', $user->group->id);
            })
            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user) {
                return $query->whereHas('order', function ($q) use ($user) {
                    $q->where('exporter_id', $user->id);
                });
            });

        if ($searchByDate == true) {
            $initial_date = $request->input('initial_date');
            $final_date = $request->input('final_date');
            $query->whereBetween('transactions.created_at', [$initial_date, $final_date]);

        }

        if ($importer_id) {
            $query->orWhereHas('order', function ($query) use ($importer_id) {
                $query->where('owner_id', $importer_id);
            });
        }

        if ($transaction_types) {
            $query->whereIn('transaction_type_id', $transaction_types);
        }

        if ($terms) {
            $query->where(function ($query) use ($terms) {
                $query->where('bank', 'like', "%$terms%")
                    ->orWhere('description', 'like', "%$terms%")
                    ->orWhereHas('order', function ($query) use ($terms) {
                        $query->where('number', 'like', "%$terms%");
                    })
                    ->orWhereHas('order.mapa', function ($query) use ($terms) {
                        $query->where('nfe_key', 'like', "%$terms%");
                    });
            });
        }

        $transactions = $query->get();

        $transactions->each(function ($transaction) {
            if ($transaction['order'] === null) return false;

            if ($transaction['transaction_type_id'] != 2 && $transaction['transaction_type_id'] != 5) {
                $mainDollarValue = \App\Transaction::where('order_id', $transaction['order_id'])
                    ->where('transaction_type_id', 1)->first();
                $transaction['main_dollar_value'] = $mainDollarValue['dollar_value'];
            } else {
                $transaction['main_dollar_value'] = 0;
            }
            $transaction->transaction_type = $transaction->transaction_type()->first();
            $transaction->owner = $transaction->order()->first()->owner()->first();
            $transaction->order = $transaction->order()->with(['mapa'])->first();

            $transaction->qty_ton = $transaction->order->items->sum(function ($item) {
                return floatval($item->net_weight);
            });

            return true;
        });

        return response()->json(array_values($transactions->toArray()));
    }

    public function transactionsReportPDF(Request $request, Transaction $transaction)
    {

        $columns = $request->input('columns');
        $initial_date = $request->input('initial_date');
        $final_date = $request->input('final_date');
        $importer_id = $request->input('importer_id');
        $transaction_types = $request->input('transaction_types');
        $searchByDate = $request->input('searchByDate');
        $terms = $request->input('terms');

        $user = User::getUserLogged();

        $query = transaction::with(['order', 'order.mapa'])
            ->whereHas('order', function ($query) use ($user) {
                $query->where('group_id', $user->group->id);
            })
            ->when($user->roles->first()->name == "Exporter", function ($query) use ($user) {
                return $query->whereHas('order', function ($q) use ($user) {
                    $q->where('exporter_id', $user->id);
                });
            });

        if ($searchByDate == true) {
            $initial_date = $request->input('initial_date');
            $final_date = $request->input('final_date');
            $query->whereBetween('transactions.created_at', [$initial_date, $final_date]);

        }

        if ($importer_id) {
            $query->orWhereHas('order', function ($query) use ($importer_id) {
                $query->where('owner_id', $importer_id);
            });
        }

        if ($transaction_types) {
            $query->whereIn('transaction_type_id', $transaction_types);
        }

        if ($terms) {
            $query->where(function ($query) use ($terms) {
                $query->where('bank', 'like', "%$terms%")
                    ->orWhere('description', 'like', "%$terms%")
                    ->orWhereHas('order', function ($query) use ($terms) {
                        $query->where('number', 'like', "%$terms%");
                    })
                    ->orWhereHas('order.mapa', function ($query) use ($terms) {
                        $query->where('nfe_key', 'like', "%$terms%");
                    });
            });
        }

        $query->each(function ($data) use (&$total, &$total_converted, &$total_variation) {
            $total += $data['value'];
            $total_converted += $data['converted_value'];
            $total_variation += $data['transaction_type_id'] == Transaction::EXCHANGE && ($data['value'] * $data['dollar_value']) - ($data['value'] * $data['main_dollar_value']);
        });

        $transactions = $query->get();

        $data = [
            "transactions" => $transactions,
            "total" => $total,
            "total_converted" => $total_converted,
            "total_variation" => $total_variation,
            "columns" => $columns,
            "initial_date" => $initial_date,
            "final_date" => $final_date,
        ];

        $url = $this->pdf->getFile(
            'documents.transactions',
            'transactions',
            $data,
            'landscape'
        );

        $file = Storage::download($url);
        return $file;
    }
}
