<?php

namespace App\Jobs;

use App\User;
use App\LogEmail;
use App\Mail\RenderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BaseJob
{

    public function saveLogEmail($inputs)
    {
        User::setUserLogged($inputs['user']);

        $error = null;
        $status = LogEmail::STATUS_SUCCESS;

        if(!isset($inputs['senders'])){
            $inputs['senders'] = [];
        }

        $log_email = LogEmail::firstOrNew(['id' => 0]);
        $log_email->user_id = $inputs['user']->id;
        $log_email->from_name = \Config::get('mail.from.name');
        $log_email->from_email = \Config::get('mail.from.address');
        $log_email->recipients = implode(';',array_unique(array_merge($inputs['recipients'],$inputs['senders'])));
        $log_email->subject = $inputs['subject'];
        $log_email->save();

        return $log_email;
    }
}
