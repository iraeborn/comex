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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class OrderFumigationJob extends BaseJob implements ShouldQueue 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $order;
    private $shipping;
    private $subject;
    private $view;

    public function __construct($order)
    {
        $this->order = $order;

        $this->shipping = \App\Shipping::where('order_id', $this->order->id)->first();
        if (!$this->shipping) {
            $this->shipping = new \App\Shipping();
        }
        
        $this->subject = 'Solicitação Emissão  Draft Certificados da Fumigadora PO '.$order->number.' Booking '. $this->shipping->booking;
        $this->view = 'mail.order_fumigation_draft_documents';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->order || !$this->order->group || !$this->order->exporter)return;

        $user_fumigation_email = DB::table('orders_fumigation')->where('id', $this->order->id)->value('email');
        $user_fumigation_userid = DB::table('orders_fumigation')->where('id', $this->order->id)->value('user_id');
        $contacts_fumigation_emails = DB::table('contacts')->where('user_id', $user_fumigation_userid)->get();

        $recipients = [];
        $recipients[] = $user_fumigation_email;
        foreach($contacts_fumigation_emails as $data){
            $recipients[] = $data->value;
        }


        $senders = $this->order->getExporterEmails();

        $params = [
            'user' => $this->order->exporter,
            'subject' => $this->subject,
            'view' => $this->view,
            'order' => $this->order,
            'recipients' => $recipients,
            'senders' => $senders,
            'params' => [
                'order' => $this->order,
                'booking' => $this->shipping->booking,
                'senders' => $senders,
                'show_approval' => true
            ]
        ];

        $log_email = $this->saveLogEmail($params);

        try {
            $recipients = [];
            $recipients[] = $user_fumigation_email;
            foreach($contacts_fumigation_emails as $data){
                $recipients[] = $data->value;
            }

            $senders = $this->order->getExporterEmails();
            
            //SEND TO IMPORTER
            \Mail::to($params['recipients'])
            ->send(new RenderMail($params));

            //SEND TO EXPORTER
            // if(count($senders)>0){
            //     $params['params']['show_approval'] = false;
            //     \Mail::to($senders)
            //     ->send(new RenderMail($params));
            // }
            
            $error = null;
            $status = LogEmail::STATUS_SUCCESS;

        } catch (\Exception $e) {
            $error = $e->getMessage().' in '.$e->getFile().' '.$e->getLine();
            $status = LogEmail::STATUS_ERROR;
        }
        finally{
            $log_email->error = $error;
            $log_email->status = $status;
            $log_email->save();

            if(\Config::get('app.env') == 'local'){
                throw new \Exception($error);
            }
        }
    }
}
