<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;

use App\Models\Appointment;
use App\Models\GoToMeeting;
use App\Models\PaymentJob;

class AppointmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Appointment instance.
     *
     * @var \App\Models\Appointment
     */
    public $appointment;
    public $payment;
    public $gotoMeeting;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, PaymentJob $payment = null, GoToMeeting $gotoMeeting = null)
    {
        $this->appointment = $appointment;
        $this->payment = $payment;
        $this->gotoMeeting = $gotoMeeting;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your appointment has been approved!')->view('emails.appointment_approved');
    }
}
