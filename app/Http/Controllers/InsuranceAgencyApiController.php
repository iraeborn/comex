<?php

namespace App\Http\Controllers;

use App\InsuranceObservation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\OrderInsuranceAgencyJob;
use App\Order;
use App\User;

class InsuranceAgencyApiController extends Controller
{
    public function index(\Request $request, $order_id)
    {
        $order = \App\Order::find($order_id);
        $name = \App\User::find($order->first()->weight_id)->name;

        $insurance_object = [
            'name' => $name,
        ];

        return response()->json($name);
    }

    public function post(\Request $request, $order_id)
    {
        $order = \App\Order::find($order_id);
        $proforma = \Request::input('order')['proforma'];
        $proforma_object = $order->documents()->where('document_type_id', 3)->first();
        $proforma_object->url = $proforma;
        $proforma_object->save();


        return response()->json();
    }

    public function getObservation(Request $request, $orderId)
    {

        $order = Order::findOrFail($orderId);

        $observations = InsuranceObservation::where('order_id', $order->id)->get();
        foreach ($observations as &$observation) {
            $observation->name = $observation->user->name;
            $observation->me = $observation->user_id == $request->get('user_id');
            $observation->avatar = $observation->user->profile_picture ? $observation->user->profile_picture : User::DEFAULT_AVATAR;
        }
    
        return response()->json($observations);
    }

    public function addObservation(\Request $request, $order_id)
    {
        $observation = new \App\InsuranceObservation();
        $user = \App\User::find(\Request::get('user_id'));

        $observation->text = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->order_id = $order_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }

    public function getProfile(\Request $request, $order_id)
    {
        $order = \App\Order::find($order_id);
        $name = \App\User::find($order->weight_id)->name;

        $insurance_object = [
            'id' => $order_id,
            'name' => $name,
            'draft_documents' => $order->draft_documents()->first(),
            'order' => (object) [
                'proforma' => $order->documents()->where('order_id', $order_id)->where('document_type_id', 3)->first()->url
            ]
        ];

        return response()->json($insurance_object);
    }

    public function putProfile(\Request $request, $order_id)
    {
        $order = \App\Order::find($order_id);
        $name = \App\User::find($order->weight_id)->name;

        $draft = $order->draft_documents()->first();
        $draft->certificate_seguro = \Request::input('draft_documents')['certificate_seguro'];

        $draft->save();

        return response()->json(['success' => true]);
    }

}