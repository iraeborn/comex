<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillsApiController extends Controller
{
    public function index ( \Request $request, $id ) {
        $bills = \App\Bill::where('order_id', $id)->get();

        foreach ($bills as &$bill) {
            $bill->truck = \App\Vehicle::find($bill->truck_id);
        }

        return response()->json($bills);
    }

    public function put ( \Request $request, $bill_id ) {
        $bill = \App\Bill::find($bill_id)->get();

        $bill->container_id = \Request::input('container_id');

        $bill->save();

        return response()->json(['success' => true]);
    }

    public function post ( \Request $request, $bill_id ) {
        $bill = \App\Bill::find($bill_id);

        $bill->damaged          = \Request::input('damaged');
        $bill->lack             = \Request::input('lack');
        $bill->leftovers        = \Request::input('leftovers');
        $bill->physical_balance = \Request::input('physical_balance');
        $bill->total            = \Request::input('total');

        $saved = $bill->save();

        return response()->json(['success' => $saved]);
    }
}
