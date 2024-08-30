<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RailroadApiController extends Controller
{
    public function index ( \Request $request, $id ) {
        $order = \App\Order::find($id);
        $railroads = $order->railroads()->first();

        return response()->json($railroads);
    }

    public function put ( \Request $request, $id ) {
        $validator = \Validator::make(\Request::all(), $this->getRules(0));

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $order = \App\Order::find($id);
        $railroad = $order->railroads()->first();

        if(!$railroad) $railroad = new \App\Railroad();

        $railroad_input = \Request::input();
        
        // $railroad->fill($railroad_input);
        $railroad->arrival_date               = \Request::input('arrival_date');
        $railroad->estimated_time             = \Request::input('estimated_time');
        $railroad->final_date                 = \Request::input('final_date');
        $railroad->order_id                   = \Request::input('order_id');
        $railroad->start_date                 = \Request::input('start_date');
        $railroad->withdrawal_date            = \Request::input('withdrawal_date');
        $railroad->withdrawal_start_date      = \Request::input('withdrawal_start_date');
        $railroad->transfer_terminal_date     = \Request::input('transfer_terminal_date');
        $railroad->terminal_confirmation_date = \Request::input('terminal_confirmation_date');
        $railroad->freetime_date              = \Request::input('freetime_date');
        $railroad->depot_empty                = \Request::input('depot_empty');
        $railroad->order_id = $id;

        $railroad->save();

        return response()->json(['success' => 'Saved!']);
    }

    public function addObservation ( \Request $request, $railway_id ) {
        $railway = \App\Railroad::find($railway_id);
        $observation = new \App\RailwayObservation();
        $user = \App\User::find(\Request::get('user_id'));

        $observation->text    = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->order_id = $railway->order_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }

    public function getObservation ( \Request $request, $railway_id ) {
        $railway = \App\Railroad::find($railway_id);

        $observations = $railway->observations()->get();

        foreach ($observations as &$observation) {
            $user = \App\User::find($observation->user_id);
            $observation->name = $user->name;
            $observation->me = $observation->user_id == \Request::get('user_id');
            $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;
        }

        return response()->json($observations);
    }


    protected function getRules ($id) {
        $rule = [
            /*'arrival_date'               => 'required',
            'estimated_time'             => 'required',
            'final_date'                 => 'required',
            'order_id'                   => 'required',
            'start_date'                 => 'required',
            'withdrawal_date'            => 'required',
            'withdrawal_start_date'      => 'required',
            'transfer_terminal_date'     => 'required',
            'terminal_confirmation_date' => 'required',
            'freetime_date'              => 'required',*/
        ];

        return $rule;
    }
}
