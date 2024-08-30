<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function index()
    {
        $current_user = \App\User::find(\Session::get('user_id'));
        
        if(!$current_user->api_token) {
            \Session::flush();
            return redirect('');
        }

        $current_user->role_list = array_map(array($this, 'getNames'),$current_user->roles->toArray());

        $current_user->contacts = $current_user->contacts->toArray();

        $roles = \App\Role::all();

        return view('spa')->with(compact('current_user'))->with(compact('roles'));
    }

    function getNames ($element) {
        return $element['name'];
    }
}
