<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NewUserRegistred;

class SettingsApiController extends Controller
{
    public function get () {
        $mail = \App\Mail::first();

        $settings = [];
        if ($mail) {
            $settings['mail'] = $mail;
        } else {
            $settings['mail'] = new \App\Mail();
        }

        return response()->json($settings);
    }

    public function put () {
        $mail = \App\Mail::first();

        $mail->fill(\Request::input('mail'));

        $mail->save();

        return response()->json(['success' => 'Settings saved!']);
    }

    public function emailTest (\Request $request){
        $mail = \Mail::to('comercial@agricolalucas.com.br')->bcc('2732629@gmail.com')->send(new NewUserRegistred([
                    'name' => 'POPCORN INDUSTRIA E COMERCIO DE CEREAIS LTDA',
                    'link' => "change-password?token=9390453d9dc25bf3&email=comercial@agricolalucas.com.br"
                ]));
        return response()->json($mail);
    }
}
