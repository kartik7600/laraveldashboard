<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class MessageMail extends Mailable
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
        //dd($this->user->subject);

        return $this->from('dashboard@aladdinseo.com', 'Themmar')
                    ->subject($this->user->subject)
                    ->markdown('admin.messages.email.message-email')
                    ->with(['manager' => $this->user]);

        //return $this->markdown('messages.email.message-email');
    }
}
