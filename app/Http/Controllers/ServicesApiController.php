<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Mail\TestSmtp;
use App\Mail\RenderMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;

class ServicesApiController extends Controller
{
    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function __construct()
    {
    }

    public function get(Request $request, $id = null)
    {   
        $user = \App\User::getUserLogged();

        $obj = \App\Service::where('id','>',0)
        ->when($request->input('active'),function($q) use($request){
            $q->where('active',$request->input('active'));
        })
        ->orderBy('name');

        if($id){
            return response()->json($obj->where('id',$id)->first());
        }

        return response()->json($obj->get());
    }

    public function post($id = null)
    {
        $user = \App\User::getUserLogged();

        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        $obj = \App\Service::firstOrNew(['id' => $id]);
        $obj->name          = \Request::input('name');
        $obj->active          = \Request::input('active');
        $obj->save();

        return response()->json(['success' => 'Group is successfully added.', 'group_id' => $obj->id]);
    }

    public function delete(\Request $request, $id)
    {
        $obj = \App\Service::find($id);

        if (!$obj) {
            return response()->json(['error' => 'This group cannot be founded.']);
        }

        if($item = \App\ProviderContract::where('service_id',$obj->id)->first()){
            throw new \Exception("Não é possível remover o registro `".$obj->name."` porque está vinculado com o contrato `".$item->identifier."`");
        }

        $obj->delete();

        return response()->json(['success' => 'Service is sucessfully deleted.']);
    }

    protected function getRules($id)
    {
        $rule = [
            'name'            => 'required',
            'active'            => 'required|numeric'
        ];

        return $rule;
    }
}
