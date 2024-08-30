<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortsApiController extends Controller
{
    public function index () {
        $ports = \App\Port::all();

        return response()->json($ports);
    }

    public function put ( \Request $request ) {
        $name        = \Request::input('name');
        $code        = \Request::input('code');


        $port     = \App\Port::firstOrNew(['name' => $name]);
        $port->name = $name;
        $port->code = $code;
        $port->save();

        return response()->json([
            'success' => 'Port inserted successfully.',
            'port_id' => $port->id
        ]);
    }
}
