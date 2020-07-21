<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class VatReprotSubmit extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /*$from = [
                    'address' => 'dashboard@aladdinseo.com',
                    'name' => 'Themmar'
                ];*/

        return $this->from('dashboard@aladdinseo.com', 'Themmar')
                    ->subject('VAT Report Submitted')
                    ->markdown('admin.vat_report_submitted.emails.vatreportsubmit')
                    ->with(['users' => $this->user]);
    }
}
