<?php

namespace App\Actions\Transactions;

use App\Order;
use App\Transaction;
use Illuminate\Http\Request;

class NewTransactionAction
{
    public function __invoke(Request $request, $order_id)
    {

        $balance = $request->input('balance');
        $transactionType = $request->input('type');
        $description = $request->input('description');
        $bank = $request->input('bank');
        $dollarValue = $request->input('dollar_value');
        $convertedValue = $request->input('converted_value');
        $cambioContract = $request->input('cambio_contract');
        $reference = $request->input('reference');
        $data = $request->input('data');
        
        if ($transactionType == 1) {
            $existingTransaction = Transaction::where('order_id', $order_id)
            ->where('transaction_type_id', 1)
            ->exists();

            if ($existingTransaction) {
                return ['success' => false, 'message' => "Initial balance already exists for this order"];
            }

            if (!$balance) {
                return ['success' => false, 'message' => "Balance cannot be zero or null"];
            }

            $balance = preg_replace('/[\.,]/', '', $balance);
            $balance = intval($balance);

            if (preg_match('/[\.,]\d{2}/', $request->input('balance'))) {
                $balance /= 100;
            }

            $balance = -abs($balance);
        }
        
        $transaction = new Transaction();
        $transaction->value = $balance;
        $transaction->transaction_type_id = $transactionType;
        $transaction->description = $description;
        $transaction->bank = $bank;
        $transaction->dollar_value = $dollarValue;
        $transaction->converted_value = $convertedValue;
        $transaction->cambio_contract = $cambioContract;
        $transaction->reference = $reference;
        $transaction->data = $data;
        $transaction->order_id = $order_id;
        
        $order = Order::find($order_id);
        $order->expiry_date = $request->input('expiry_date');
        $order->nf = $request->input('nf');
        $order->save();

        $saved = $transaction->save();

        return ['success' => $saved, 'transaction' => $transaction];
    }
}
