<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    function index (\Request $request) {
    }

    function get (\Request $request) {
        var_dump($request);
    }

    function post (\Request $request) {
        $item = new \App\Item();
        
        $validator = \Validator::make(\Request::all(), $this->getRules(0));

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }


        return response()->json(['success'=>'Item is successfully added.']);

    }

    function put (\Request $request) {
        var_dump($request);
    }

    function delete (\Request $request) {
        var_dump($request);
    }

    protected function getRules ($id) {
        $rule = [
            'description'  => 'required|min:5',
            'crop_year'    => 'required|min:4|max:4',
            'quantity'     => 'required|numeric|min:1',
            'unit_price'   => 'required|numeric|min:1',
            'total_price'  => 'required|numeric|min:1',
            'value'        => 'required|min:10|max:200',
            'total_bags'   => 'required|numeric|min:1',
            'net_weight'   => 'required|numeric|min:1',
            'gross_weight' => 'required|numeric|min:1',
            'container'    => 'required',
            'advance_payment' => 'required'
        ];

        return $rule;
    }

}
