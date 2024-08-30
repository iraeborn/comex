<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Documents\DocumentController as DocumentsDocumentController;
use Illuminate\Http\Request;
use App\Bank;
use Carbon\Carbon;

class BanksApiController extends Controller
{

    protected $obj;

    public function __construct(Bank $obj)
    {
        $this->obj = $obj;
    }

    protected $messages = [
        'items.required' => 'At last one item is required'
    ];

    public function getBanks()
    {
        $user = \App\User::getUserLogged();

        return $this->obj->whereNull('entity_id')->whereHas('user', function ($q) use ($user) {
            $q
                ->whereRaw('? = 1', $user->group->id)
                ->orWhere('group_id', $user->group->id)
                ->orWhere('user_id', $user->id);
        })->get();
    }

    public function get(\Request $request, $id)
    {
        $banks = $this->obj->find($id);

        return response()->json($banks);
    }

    public function post(Request $request, Bank $bank)
    {
        $user = \App\User::getUserLogged();
        $validator = \Validator::make(\Request::all(), $this->getRules(0));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $bank->user_id              = $user->id;
        $bank->beneficiary_company  = $request->input('beneficiary_company');
        $bank->beneficiary_name     = $request->input('beneficiary_name');
        $bank->beneficiary_iban     = $request->input('beneficiary_iban');
        $bank->beneficiary_swift    = $request->input('beneficiary_swift');
        $bank->beneficiary_branch   = $request->input('beneficiary_branch');
        $bank->beneficiary_agency   = $request->input('beneficiary_agency');
        $bank->beneficiary_account  = $request->input('beneficiary_account');
        $bank->beneficiary_clearing = $request->input('beneficiary_clearing');
        $bank->beneficiary_chips    = $request->input('beneficiary_chips');

        $bank->intermediary_company = $request->input('intermediary_company');
        $bank->intermediary_name    = $request->input('intermediary_name');
        $bank->intermediary_iban    = $request->input('intermediary_iban');
        $bank->intermediary_swift   = $request->input('intermediary_swift');
        $bank->intermediary_branch  = $request->input('intermediary_branch');
        $bank->intermediary_agency  = $request->input('intermediary_agency');
        $bank->intermediary_account = $request->input('intermediary_account');
        $bank->intermediary_clearing = $request->input('intermediary_clearing');
        $bank->intermediary_chips   = $request->input('intermediary_chips');

        $bank->group_id             = $user->group->id;
        $bank->entity               = 'Banks';
        $bank->entity_id            = NULL;
        $bank->created_at           = Carbon::now();
        $bank->updated_at           = Carbon::now();

        $bank->save();

        return response()->json(['success' => 'Bank is successfully added.']);
    }

    public function put(\Request $request, $id)
    {

        $user = \App\User::getUserLogged();
        $validator = \Validator::make(\Request::all(), $this->getRules(0), $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $bank = Bank::find($id);
        $bank->user_id              = $user->id;
        $bank->beneficiary_company  = \Request::input('beneficiary_company');
        $bank->beneficiary_name     = \Request::input('beneficiary_name');
        $bank->beneficiary_iban     = \Request::input('beneficiary_iban');
        $bank->beneficiary_swift    = \Request::input('beneficiary_swift');
        $bank->beneficiary_branch   = \Request::input('beneficiary_branch');
        $bank->beneficiary_agency   = \Request::input('beneficiary_agency');
        $bank->beneficiary_account  = \Request::input('beneficiary_account');
        $bank->beneficiary_clearing = \Request::input('beneficiary_clearing');
        $bank->beneficiary_chips    = \Request::input('beneficiary_chips');

        $bank->intermediary_company  = \Request::input('intermediary_company');
        $bank->intermediary_name     = \Request::input('intermediary_name');
        $bank->intermediary_iban     = \Request::input('intermediary_iban');
        $bank->intermediary_swift    = \Request::input('intermediary_swift');
        $bank->intermediary_branch   = \Request::input('intermediary_branch');
        $bank->intermediary_agency   = \Request::input('intermediary_agency');
        $bank->intermediary_account  = \Request::input('intermediary_account');
        $bank->intermediary_clearing = \Request::input('intermediary_clearing');
        $bank->intermediary_chips    = \Request::input('intermediary_chips');

        $bank->entity                = 'Banks';
        $bank->entity_id             = NULL;
        $bank->updated_at            = \Carbon\Carbon::now();
        $bank->save();

        return response()->json(['success' => 'Bank is successfully updated.']);
    }

    public function delete($id)
    {
        $bank = Bank::find($id);
        $bank->delete();
        return response()->json(['success' => 'Bank is successfully deleted.']);
    }
    protected function getRules($id)
    {
        $rule = [
            'beneficiary_company'   => 'required',
            'intermediary_company'  => 'required'
        ];

        return $rule;
    }
}
