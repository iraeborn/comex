<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistredRecovery extends Mailable
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
        return $this->from('nao-responda@agricolalucas.com.br', "Popcorn")
                    ->subject('Welcome!')
                    ->markdown('mail.new_user')
                    ->with([
                        'name' => $this->inputs['name'],
                        'link' => \URL::to($this->inputs['link'])
                    ]);
    }
}
