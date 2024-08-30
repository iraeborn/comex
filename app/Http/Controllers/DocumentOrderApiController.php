<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;
use Illuminate\Http\Request;
use App\DocumentOrder;
use App\User;

class DocumentOrderApiController extends Controller
{

    protected $orderDocument;

    public function __construct(DocumentOrder $orderDocument){
        $this->orderDocument = $orderDocument;
    }

    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function getOrderDocuments(){
        $user = User::getUserLogged();
        
        $documents = DocumentOrder::where(function ($query) use ($user) {
            $query
                ->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
        ->get();
        return $documents;
    }

    public function get (Request $request, $id) {
        $obj = \App\DocumentOrder::find($id);

        return response()->json($obj);
    }

    public function post (Request $request) {
        $validator = \Validator::make($request->all(), $this->getRules(0), $this->messages);
        $user = \App\User::getUserLogged();
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $obj = new DocumentOrder();
        $obj->description        = $request->input('description');
        $obj->created_at         = \Carbon\Carbon::now();
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->group_id          = $user->group->id;
        $obj->save();

        return response()->json(['success'=>'Document is successfully added.']);
    }

    public function put ( \Request $request, $id ) {

        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $obj = \App\DocumentOrder::find($id);

        $obj->description        = \Request::get('description');
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->save();

        return response()->json(['success'=>'Document is successfully updated.']);
    }

    public function copy ( \Request $request, $id ) {

        if(!$template = \App\DocumentOrder::find($id)){
            return response()->json(['error' => 'This document cannot be founded.']);
        }

        $obj = new \App\DocumentOrder();
        $obj->description        = $template->description;
        $obj->created_at         = \Carbon\Carbon::now();
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->save();
        
        return response()->json(['success'=>'Document is successfully copied.']);
    }

    public function delete(\Request $request, $id)
    {
        $obj = \App\DocumentOrder::find($id);

        if (!$obj) {
            return response()->json(['error' => 'This document cannot be founded.']);
        }

        $obj->delete();

        return response()->json(['success' => 'Document is sucessfully deleted.']);
    }

    protected function getRules ($id) {
        $rule = [
            'description'            => 'required'
        ];

        return $rule;
    }
}