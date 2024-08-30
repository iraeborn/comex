<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContainerApiController extends Controller
{
    protected $messages = [
            'name' => 'The container is required.',
        ];

    public function index () {}
    public function get () {}
    public function post () {
        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $user = new \App\User();

        $request_container = \Request::input();
        if (!array_key_exists('id', $request_container)){
            $container = new \App\Container();
        } else {
            $container = \App\Container::find($request_container['id']);
        }

        $container->fill($request_container);
        $container->save();

        foreach ($request_container['selected'] as $bill_id) {
            $bill = \App\Bill::find($bill_id);
            
            if($bill) {
                $bill->container_id = $container->id;
                $bill->save();
                //$bill = new \App\Bill();
            }
            
        }

        return response()->json([
            'success' => $container
        ]);
    }
    public function put () {}
    public function delete (\Request $request, $container_id) {
        \App\Container::find($container_id)->delete();

    }

    protected function getRules ($id) {
        $rule = [
            'name' => 'required|min:1|max:80',
            'tare' => 'required',
            'seal' => 'required',
        ];

        return $rule;
    }

}
