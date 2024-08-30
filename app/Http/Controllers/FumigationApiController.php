<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\OrderFumigationJob;

class FumigationApiController extends Controller
{
    public function index ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $fumigation = $order->fumigation()->first();

        if (!$fumigation) {
            $fumigation = new \App\Fumigation();
            $fumigation->order_id = $order_id;
            $fumigation->save();
        }

        $fumigation->name = "Agency is not defined";
        if($order->inspection_id) {
            if(\App\User::find($order->inspection_id)) {
                $fumigation->name = \App\User::find($order->fumigation_id)->name;
            }

            $fumigation->draft_fumigation_certificate = $fumigation->draft_files();
            $fumigation->original_fumigation_certificate = $fumigation->original_files();
        }

        
        if(\Request::has('sending_emails') && in_array('fumigation',\Request::input('sending_emails'))){
            $this->dispatch(new OrderFumigationJob($order));
        }
        

        return response()->json($fumigation);
    }

    public function put ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $fumigation = $order->fumigation()->first();

        if (!$fumigation) {
            $fumigation = new \App\Fumigation();
            $fumigation->order_id = $order_id;
            $fumigation->save();
        }

        $fumigation->fill(\Request::input());

        $fumigation->save();

        return response()->json(['success' => true]);
    }

    public function getObservation ( \Request $request, $fumigation_id ) {
        $fumigation = \App\Fumigation::find($fumigation_id);

        $observations = $fumigation->observations()->get();
        foreach ($observations as &$observation) {
            $user = \App\User::find($observation->user_id);
            $observation->name = $user->name;
            $observation->me = $observation->user_id == \Request::get('user_id');
            $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;
        }

        return response()->json($observations);
    }

    public function addObservation ( \Request $request, $fumigation_id ) {
        $user = \App\User::find(\Request::get('user_id'));
        $observation = new \App\FumigationObservation();

        $observation->text    = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->fumigation_id = $fumigation_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }

    public function putProfile ( \Request $request, $order_id ) {
        $order = \App\Order::find($order_id);
        $fumigation = $order->fumigation()->first();

        $draft = \App\DraftDocuments::where('order_id', $order_id)->first();
        $original = \App\OriginalDocument::where('order_id', $order_id)->where('original_documents_type_id', 5)->first();

        if (!$original) {
            $original = new \App\OriginalDocument();
            $original->order_id = $order_id;
            $original->original_documents_type_id = 5;
            $original->original_documents_status_id = 2;
        }

        $url = \Request::input('draft_fumigation_certificate');
        if (is_array(\Request::input('draft_fumigation_certificate'))) {
            $url = \Request::input('draft_fumigation_certificate')['url'];
        }
        $draft->certificate_fumigation = $url;
        $original->url = is_array(\Request::input('original_fumigation_certificate')) ? \Request::input('original_fumigation_certificate')['url'] : \Request::input('original_fumigation_certificate');

        $draft->save();
        $original->save();

        return response()->json(['success' => true]);
    }
}
