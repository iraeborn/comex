<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    public function index()
    {
        $message = '';
        return view('login')->with('message');
    }

    public function doLogin(Request $request)
    {
        $user = User::where('username', $request->post('username'))->first();
        logger()->info('login 1 ' . json_encode($user));

        if (!$user) {
            $message = 'User not found!';
            return view('login', compact('message'));
        }

        logger()->info('login 2');

        if (!Hash::check($request->post('password'), $user->password)) {
            $message = 'Password don`t match!';
            logger()->info('login failed password => ' . $user->username . ' = ' . Hash::make('280220'));
            return view('login', compact('message'));
        }

        $request->session()->put('user_id', $user->id);

        $user->last_login_at = date('Y-m-d H:i:s');

        if (!$user->api_token) {
            $api_token = md5(uniqid(rand(), true));
            $user->api_token = $api_token;
        }

        $user->save();
        return redirect('dashboard');
    }

    public function doLogout(Request $request)
    {
        Session::flush();
        return redirect('');
    }

    public function changePassword(Request $request)
    {
        $token = $request->input('token');
        $email = $request->input('email');

        $user = User::where('email', $email)->where('token', $token)->first();

        if (!$user) {
            return "User not founded";
        }

        return view('users.change_password')->with(compact('user'));
        return;
    }

    public function updatePassword(Request $request)
    {
        $token = $request->input('token');
        $email = $request->input('email');

        $user = \App\User::where('email', $email)->where('token', $token)->first();

        if (!$user) {
            return view('users.not_founded');
        }

        $user->token = null;
        $user->password = Hash::make($request->input('new_password'));

        $request->session()->put('user_id', $user->id);

        $user->save();

        return redirect('dashboard');
    }
}
