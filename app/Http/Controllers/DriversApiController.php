<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;
use Illuminate\Http\Request;
use App\Driver;

class DriversApiController extends Controller
{

    protected $obj;

    public function __construct(Driver $obj)
    {
        $this->obj = $obj;
    }

    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function getDrivers()
    {
        // return $this->obj->orderBy('name', 'ASC')->get();

        $user = \App\User::getUserLogged();
        
        $driver = Driver::where(function ($query) use ($user) {
            $query
                ->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
        ->orderBy('name', 'ASC')
        ->get();

        return $driver;
    }

    public function get(\Request $request, $id)
    {
        $obj = \App\Driver::find($id);

        return response()->json($obj);
    }

    public function post(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->getRules(0), $this->messages);
        $user = \App\User::getUserLogged();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $obj = new Driver();
        $obj->name          = $request->input('name');
        $obj->cnh           = $request->input('cnh');
        $obj->rg            = $request->input('rg');
        $obj->cpf           = $request->input('cpf');
        $obj->born_at       = $request->input('born_at');
        $obj->phone         = $request->input('phone');
        $obj->group_id      = $user->group->id;
        $obj->created_at    = \Carbon\Carbon::now();
        $obj->updated_at    = \Carbon\Carbon::now();
        $obj->save();

        return response()->json(['success' => 'Driver is successfully added.']);
    }

    public function put(\Request $request, $id)
    {

        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $obj = \App\Driver::find($id);

        $obj->name        = \Request::input('name');
        $obj->cnh         = \Request::input('cnh');
        $obj->rg         = \Request::input('rg');
        $obj->cpf         = \Request::input('cpf');
        $obj->born_at         = \Request::input('born_at');
        $obj->phone         = \Request::input('phone');
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->save();

        return response()->json(['success' => 'Driver is successfully updated.']);
    }

    public function delete(\Request $request, $id)
    {
        $driver = \App\Driver::find($id);

        if (!$driver) {
            return response()->json(['error' => 'This driver cannot be founded.']);
        }

        if($truck = \App\Vehicle::where('driver_id',$driver->id)->first()){
            throw new \Exception("Não é possível remover o registro `".$driver->name."` porque está vinculado com o veículo `".$truck->plate."`");
        }

        $driver->delete();

        return response()->json(['success' => 'Client is sucessfully deleted.']);
    }

    protected function getRules($id)
    {
        $rule = [
            'name'            => 'required',
            'phone'            => 'required',
            'cnh'            => 'required',
            'rg'            => 'required',
            'cpf'            => 'required',
            'born_at'            => 'required',
        ];

        return $rule;
    }
}
