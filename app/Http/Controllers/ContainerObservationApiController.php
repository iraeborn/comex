<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContainerObservationApiController extends Controller
{
    public function index ( \Request $request, $container_id ) {
        $containerStuffing = \App\ContainerStuffing::find($container_id);

        $observations = $containerStuffing->observations()->get();
        foreach ($observations as &$observation) {
            $user = \App\User::find($observation->user_id);
            $observation->name = $user->name;
            $observation->me = $observation->user_id == \Request::get('user_id');
            $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;
        }

        return response()->json($observations);
    }

    public function post ( \Request $request, $container_id ) {
        $user = \App\User::find(\Request::get('user_id'));
        $observation = new \App\ContainerObservation();

        $observation->text    = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->container_id = $container_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }
}
