<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\GoToMeeting;

use Illuminate\Mail\Mailable;

class PaymentCompletedForPatient extends Mailable
{
    public $appointment;
    public $gotoMeeting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, $gotoMeeting)
    {
        $this->appointment = $appointment;
        $this->gotoMeeting = $gotoMeeting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your appointment has been confirmed!')->view('emails.payment_completed_for_patient');
    }
}
