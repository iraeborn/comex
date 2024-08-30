<?php


namespace App\Http\Controllers;


use App\Signatures;
use App\User;

class SignaturesController
{
    /**
     * @var Signatures
     */
    private $signatures;

    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    /**
     * SignaturesController constructor.
     * @param Signatures $signatures
     */
    public function __construct(Signatures $signatures)
    {
        $this->signatures = $signatures;
    }

    public function index($id = null)
    {   
        $user = User::getUserLogged();

        $signature = $this->signatures
        ->whereHas('user',function($q) use($user){
            $q 
            ->whereRaw('? = 1',$user->group->id)
            ->orWhere('group_id',$user->group->id);
        });

        if($id){
            $signature = $signature->where('id',$id)->first();
        }else{
            $signature = $signature->get();
        }

        return response()->json($signature);
    }

    public function post()
    {   
        $user = \App\User::getUserLogged();
        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $obj = Signatures::firstOrNew(['url' => \Request::input('url')]);
        $obj->user_id     = $user->id;
        $obj->text        = \Request::input('text');
        $obj->url         = \Request::input('url');
        $obj->term1         = \Request::input('term1');
        $obj->term2         = \Request::input('term2');
        $obj->save();

        return response()->json(['success' => 'Signature is successfully added.']);
    }

    public function put(\Request $request, $id)
    {

        $user = \App\User::getUserLogged();
        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $obj = Signatures::find($id);
        $obj->user_id     = $user->id;
        $obj->text        = \Request::input('text');
        $obj->url         = \Request::input('url');
        $obj->term1         = \Request::input('term1');
        $obj->term2         = \Request::input('term2');
        $obj->save();

        return response()->json(['success' => 'Signature is successfully updated.']);
    }

    public function delete(\Request $request, $id)
    {
        $obj = Signatures::find($id);

        if (!$obj) {
            return response()->json(['error' => 'This signature cannot be founded.']);
        }

        if($truck = \App\Order::where('signature_id',$obj->id)->first()){
            throw new \Exception("Não é possível remover o registro `".$obj->text."` porque está vinculado com o pedido `".$obj->id."`");
        }

        $obj->delete();

        return response()->json(['success' => 'Signature is sucessfully deleted.']);
    }

    protected function getRules($id)
    {
        $rule = [
            'text'            => 'required|max:255',
            'url'            => 'required|url',
        ];

        return $rule;
    }
}
