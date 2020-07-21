<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class VatReportSubmittedFiles extends Mailable
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
        //dd($this->user->vat_report_file);

        return $this->from('dashboard@aladdinseo.com', 'Themmar')
                    ->subject('VAT Report Submitted File')
                    ->markdown('admin.vat_report_submitted_files.email.vatreportsubmittedfile')
                    ->attach('public/uploads/vat-report-submitted-files/'.$this->user->vat_report_file)
                    ->with(['users' => $this->user]);
    }
}
