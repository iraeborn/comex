<?php

namespace App\Mail;

use App\LogEmail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RenderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $inputs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        $this->inputs = $inputs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $message = $this->subject($this->inputs['subject'])->markdown($this->inputs['view']);

        if(isset($this->inputs['params'])){
            $message->with($this->inputs['params']);
        }

        if(isset($this->inputs['attachments'])){
            foreach($this->inputs['attachments'] as $filePath){
                $email->attach($filePath);
            }
        }

        if(count($this->inputs['senders'])){
            $message->cc($this->inputs['senders']);
        }

        return $message;


    }
}
