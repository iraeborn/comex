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

class OrderShipmentForecastDetailsJob extends BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $order;
    private $subject;
    private $view;

    public function __construct($order)
    {
        $this->order = $order;
        $this->subject = 'Order Information '.$order->number.' - SHIPMENT FORECAST DETAILS';
        $this->view = 'mail.order_shipment_forecast_details';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->order || !$this->order->group || !$this->order->exporter)return;

        $recipients = $this->order->getImporterEmails();
        $senders = $this->order->getExporterEmails();

        $params = [
            'user' => $this->order->exporter,
            'subject' => $this->subject,
            'view' => $this->view,
            'order' => $this->order,
            'recipients' => $recipients,
            'senders' => $senders,
            'params' => [
                'order' => $this->order
            ]
        ];

        $log_email = $this->saveLogEmail($params);

        try {
            $recipients = $this->order->getImporterEmails();
            $senders = $this->order->getExporterEmails();

            \Mail::to($params['recipients'])
                ->send(new RenderMail($params));

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
