<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceExpiry extends Mailable
{
    use Queueable, SerializesModels;

    //protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Client)
    {
        $this->client = $Client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->client);
        $from = [
                    'address' => 'dashboard@aladdinseo.com',
                    'name' => 'Themmar'
                ];

        return $this->from($from)
                    ->subject('Service Expiry')
                    ->markdown('admin.clients.emails.service-expiry')
                    ->with(['fullname' => $this->client->fullname, 'servic_name' => $this->client->service_name, 'expiry_date' => $this->client->renew_date]);

        //return $this->markdown('sendemail.service.expiry');
    }
}
