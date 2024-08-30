<?php

namespace App\Http\Controllers;

use App\Container;
use App\Order;
use Illuminate\Http\Request;

class ContainerStuffingApiController extends Controller
{
    public function index ( \Request $request, $id ) {
        $order = \App\Order::find($id);

        $stuffing = $order->container_stuffing()->first();

        if (!$stuffing) {
            $stuffing = new \App\ContainerStuffing();
            $stuffing->order_id = $order->id;
            $stuffing->save();
        }

        return response()->json($stuffing);
    }

    public function put ( Request $request, $id ) {
        $order = \App\Order::find($id);

        $stuffing = $order->container_stuffing()->first();

        if (!$stuffing) {
            $stuffing = new \App\ContainerStuffing();
            $stuffing->order_id = $id;
        }

        $request_stuffing = \Request::input();
        unset($request_stuffing['vehicles']);
        unset($request_stuffing['containers']);

        $stuffing->fill($request_stuffing);
        $stuffing->save();

        return response()->json(['success' => true]);
    }

    public function getContainers ($order_id) {
        $order = Order::find($order_id);

        $shipping = $order->shipping()->first();

        $containers = Container::where('shipping_id', $shipping->id)->get();

        return response()->json($containers);
    }

    public function putContainers (Request $request, $order_id) {
        $isMulti = $request->input('isMulti') ? true : false;
        $order = Order::find($order_id);
        $shipping = $order->shipping()->first();
        
        $containers_id_list = array_filter(
            $request->input(),
            function ($container) {
                return array_key_exists('id', $container);
            });
        
        $containers_id_list = array_map(function ($container) {
            return $container['id'];
        }, $containers_id_list);
       
        if($isMulti){
            foreach (Container::where('shipping_id', $shipping->id)->whereNotIn('id', $containers_id_list)->get() as $container) {
                $container->delete();
            }
        }
        
        array_map(function ($container_input) {
            $container = new Container();
            

            if(array_key_exists('id', $container_input)) {
                $container = Container::find($container_input['id']);
            }

            $container->fill($container_input);

            if (array_key_exists('nfe_balance', $container_input)) {
                $container->nfe_balance      = $container_input['nfe_balance'];
            }
            if (array_key_exists('failure', $container_input)) {
                $container->failure          = $container_input['failure'];
            }
            if (array_key_exists('lack', $container_input)) {
                $container->lack             = $container_input['lack'];
            }
            if (array_key_exists('leftovers', $container_input)) {
                $container->leftovers        = $container_input['leftovers'];
            }
            if (array_key_exists('physical_balance', $container_input)) {
                $container->physical_balance = $container_input['physical_balance'];
            }
            if (array_key_exists('total', $container_input)) {
                $container->total            = $container_input['total'];
            }

            //$container->shipping_id = $shipping->id;

            $container->save();

        }, $request->input());

        return response()->json(['success' => true]);
    }

    public function putContainersStuffingData (Request $request, $order_id) {
        $order = Order::find($order_id);

        $shipping = $order->shipping()->first();

        $containers = Container::where('shipping_id', $shipping->id)->get();

        foreach ($request->input() as $container) {
            $container_model = Container::find($container['id']);

            $container_model->nfe_balance      = $container['nfe_balance'];
            $container_model->failure          = $container['failure'];
            $container_model->lack             = $container['lack'];
            $container_model->leftovers        = $container['leftovers'];
            $container_model->physical_balance = $container['physical_balance'];
            $container_model->total            = $container['total'];

            $container_model->save();
        }

        return response()->json(['success' => true]);
    }
}
