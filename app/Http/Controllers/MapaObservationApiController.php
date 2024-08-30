<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapaObservationApiController extends Controller
{
    public function index ( \Request $request, $mapa_id ) {
        $mapa = \App\Mapa::find($mapa_id);

        $observations = $mapa->observations()->get();
        foreach ($observations as &$observation) {
            $user = \App\User::find($observation->user_id);
            $observation->name = $user->name;
            $observation->me = $observation->user_id == \Request::get('user_id');
            $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;
        }

        return response()->json($observations);
    }

    public function post ( \Request $request, $mapa_id ) {
        $user = \App\User::find(\Request::get('user_id'));
        $observation = new \App\MapaObservation();

        $observation->text    = \Request::input('text');
        $observation->user_id = $user->id;
        $observation->mapa_id = $mapa_id;

        $observation->save();

        $observation->me = true;
        $observation->name = $user->name;
        $observation->avatar = $user->profile_picture ? $user->profile_picture : \App\User::DEFAULT_AVATAR;

        return response()->json(['success' => true, 'message' => $observation]);
    }
}
