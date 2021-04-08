<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\GoToMeeting;

use Illuminate\Mail\Mailable;

class PaymentCompletedForPhysician extends Mailable
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
        return $this->subject('Payment received for ' . $this->appointment->patient_name)->view('emails.payment_completed_for_physician');
    }
}
