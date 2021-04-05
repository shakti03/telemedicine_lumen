<?php

namespace App\Mail;

use App\Models\User;

use Illuminate\Mail\Mailable;

class VerifyEmail extends Mailable
{
    public $user;
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
        return $this->view('emails.verify_email');
    }
}
