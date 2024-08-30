<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\OrderShipmentForecastDetailsJob;
use App\Order;
use Illuminate\Support\Facades\Validator;

class ShippingApiController extends Controller
{
    protected $messages = [];

    public function index ( \Request $request, $order_id) {
        $shipping = \App\Shipping::where('order_id', $order_id);

        return response()->json($shipping);
    }

    public function get (\Request $request, $order_id) {
        $shipping = \App\Shipping::where('order_id', $order_id)->first();

        if (!$shipping) {
            $shipping = new \App\Shipping();
            $shipping->order_id = $order_id;
            $shipping->shipping_status_id = 1;
            $shipping->save();
        }

        $shipping->containers = $shipping->containers()->get();

        $shipping->containers = $shipping->containers->map(function ($container) use ($shipping) {
            if (!$container->withdrawal_date) {
                $container->withdrawal_date = ($shipping->order->container_stuffing ? $shipping->order->container_stuffing->empty_release_for_outbound_date : null);
            }

            if (!$container->return_date) {
                $container->return_date = ($shipping->order->container_stuffing ? $shipping->order->container_stuffing->containers_return_date : null);
            }

            return $container;
        });

        if($shipping->loading_port_id)
            $shipping->loading_port   = \App\Port::find($shipping->loading_port_id)->name;
        
        if($shipping->discharge_port_id)
            $shipping->discharge_port = \App\Port::find($shipping->discharge_port_id)->name;
    
        foreach ($shipping->containers as &$container) {
            $container->bills = \App\Bill::where('container_id', $container->id)->get()->toArray();
            $container->selected = array_map(array($this, 'selectedBills'), $container->bills);
        }

        if(!$shipping->containers)
            $shipping->containers = null;

        $shipping->approves = $shipping->approves()->get();

        foreach ($shipping->approves as &$approves) {
            if (!$approves->user_id) {
                $user_id = $shipping->order()->first()->owner_id;
            } else {
                $user_id = $approves->user_id;
            }

            $user = \App\User::find($user_id);

            $approves->name = $user->name;
        }

        return response()->json($shipping);
    }
    
    public function post () {
        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        return response()->json(['success'=>'Shipping is successfully added.']);
    }
    
    public function put ( Request $request, $id ) {
        // $validator = Validator::make($request->all(), $this->getRules(0), $this->messages);

        // if ($validator->fails())
        // {
        //     return response()->json(['errors'=>$validator->errors()]);
        // }

        $order = Order::find($id);
        $shipping = $order->shipping()->first();

        if (!$shipping) {
            $shipping = new \App\Shipping();
        }

        $shipping->fill($request->input());

        //$shipping->order_id = $order->id;

        if($request->input('reason')) {
            $shipping->shipping_status_id = 1;
            $approve = $shipping->approves()->orderBy('id', 'desc')->first();
            $approve->reason = $request->input('reason');
            $approve->save();
        }
        
        $order->shipping()->save($shipping);

        $request_list = array_map(function ($val) {
            if (array_key_exists('id', $val))
                return $val['id'];
        }, $request->input('containers'));

        foreach($shipping->containers()->get() as $container) {
            if (! in_array($container->id, $request_list)) {
                \App\ContainerExtraField::where('container_id', $container->id)->delete();
                $container->delete();
            }
        }

        foreach ($request->input('containers') as $container) {
            $cont = new \App\Container();

            if(array_key_exists('id', $container)) {
                $cont = \App\Container::find($container['id']);
            }

            $cont->name = $container['name'];
            $cont->tare = $container['tare'];
            $cont->seal = $container['seal'];
            $cont->shipping_id = $shipping->id;
            $shipping->containers()->save($cont);

            if(array_key_exists('extra_fields', $container)) {
                foreach ($container['extra_fields'] as $extra_field) {
                    $extra_field_object = new \App\ContainerExtraField();
                    $extra_field_object->name  = $extra_field['name'];
                    $extra_field_object->value = $extra_field['value'];
                    $extra_field_object->container_id = $cont->id;

                    $extra_field_object->save();
                }
            }
        }

        if($request->has('sending_emails') && in_array('shipment_forecast_details',$request->input('sending_emails'))){
            $this->dispatch(new OrderShipmentForecastDetailsJob($order));
        }

        return response()->json(['success'=>'Shipping information is successfully updated.']);
    }
    
    public function delete ( \Request $request, $id ) {
        $order = \App\Order::find($id);

        if (!$order) {
            return response()->json(['error'=>'This order cannot be founded.']);
        }

        $order->delete();

        return response()->json(['success'=>'Order is successfully deleted.']);
    }

    public function accept ( \Request $request, $id ) {
        $order = \App\Order::find($id);
        $shipping = $order->shipping()->first();
        $shipping->reason = null;
        $shipping->shipping_status_id = 2;
        $shipping->save();

        $approves = new \App\ShippingApprove();
        $approves->fill($shipping->toArray());
        $approves->shipping_id = $shipping->id;
        $approves->user_id = \Request::get('user_id');
        $approves->save();

        return response()->json(['success' => 'Shipping information has accepted.']);
    }

    public function reject ( \Request $request, $id ) {
        $order = \App\Order::find($id);
        $shipping = $order->shipping()->first();
        $shipping->reason = \Request::input('reason');
        $shipping->shipping_status_id = 3;
        $shipping->save();

        return response()->json(['success' => 'Shipping information has rejected.']);
    }

    protected function selectedBills ($bill) {
        return $bill['id'];
    }

    protected function getRules ($id) {
        $rule = [
            'booking'               => 'required',
            'loading_port_id'       => 'required',
            'discharge_port_id'     => 'required|different:loading_port_id',
            'vessel'                => 'required',
            'etd'                   => 'required|date_format:Y-m-d',
            'eta'                   => 'required|date_format:Y-m-d',
            'shipping_line'         => 'required',
            'free_time_origin'      => 'required',
            'free_time_destination' => 'required',
        ];

        return $rule;
    }

}
