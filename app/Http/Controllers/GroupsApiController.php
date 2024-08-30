<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Group;
use App\Mail\TestSmtp;
use App\Mail\RenderMail;
use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;
use Illuminate\Http\Request;

class GroupsApiController extends Controller
{
    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function __construct()
    {
    }

    public function get(\Request $request, $id = null)
    {
        $user = \App\User::getUserLogged();

        $obj = \App\Group::where(function($q) use($user){
            $q
            ->whereRaw('? = 1',$user->group->id)
            ->orWhere('id',$user->group->id);

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

        $obj = new Group([
        'name' => \Request::input('name'),
        'smtp' => \Request::input('smtp'),
        'updated_user' => $user->id,
        ]);
        //            $obj->name          = \Request::input('name');
//        $obj->smtp          = \Request::input('smtp');
//        $obj->updated_user  = $user->id;

        $obj->save();

        return response()->json(['success' => 'Group is successfully added.', 'group_id' => $obj->id]);
    }

    public function delete(\Request $request, $id)
    {
        $obj = \App\Group::find($id);

        if (!$obj) {
            return response()->json(['error' => 'This group cannot be founded.']);
        }

        if($truck = \App\Order::where('group_id',$obj->id)->first()){
            throw new \Exception("Não é possível remover o registro `".$obj->name."` porque está vinculado com o pedido `".$obj->number."`");
        }

        if($truck = \App\User::where('group_id',$obj->id)->first()){
            throw new \Exception("Não é possível remover o registro `".$obj->name."` porque está vinculado com o user `".$obj->username."`");
        }

        $obj->delete();

        return response()->json(['success' => 'Group is sucessfully deleted.']);
    }

    public function emailTest (\Request $request){
        try {
            (new \App\Group())->setEmailConfig(\Request::all());

            $user = \App\User::getUserLogged();

            $params = [
                'user' => $user,
                'subject' => 'SMTP Test Email',
                'view' => 'mail.smtp',
                'recipients' => [\Request::input('smtp')['from_email']],
                'senders' => [],
                'params' => [
                    'from_email' => \Request::input('smtp')['from_email'],
                    'from_name' => \Request::input('smtp')['from_name']
                ]
            ];
            \Mail::to(\Request::input('smtp')['from_email'])->send(new RenderMail($params));

            return response()->json(['success' => 'Email is sucessfully send.']);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    protected function getRules($id)
    {
        $rule = [
            'name'            => 'required'
        ];

        return $rule;
    }
}
