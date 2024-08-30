<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;
use Illuminate\Http\Request;
use App\Specification;

class SpecificationsApiController extends Controller
{

    protected $specification;

    public function __construct(Specification $specification){
        $this->specification = $specification;
    }

    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function getSpecifications(){
        $user = \App\User::getUserLogged();
        
        $specifications = Specification::where(function ($query) use ($user) {
            $query
                ->whereRaw('? = 1', [$user->group->id])
                ->orWhere('group_id', $user->group->id);
        })
        ->get();

        return $specifications;
    }

    public function get (\Request $request, $id) {
        $obj = \App\Specification::find($id);

        return response()->json($obj);
    }

    public function post (Request $request) {
        $validator = \Validator::make($request->all(), $this->getRules(0), $this->messages);
        $user = \App\User::getUserLogged();
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $obj = new Specification();
        $obj->description        = $request->input('description');
        $obj->created_at         = \Carbon\Carbon::now();
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->group_id          = $user->group->id;
        $obj->save();

        return response()->json(['success'=>'Specification is successfully added.']);
    }

    public function put ( Request $request, $id ) {

        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $obj = \App\Specification::find($id);

        $obj->description        = \Request::get('description');
        $obj->updated_at         = \Carbon\Carbon::now();
        $obj->save();

        return response()->json(['success'=>'Specification is successfully updated.']);
    }

    protected function getRules ($id) {
        $rule = [
            'description'            => 'required'
        ];

        return $rule;
    }
    
}
