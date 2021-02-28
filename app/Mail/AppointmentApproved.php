<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;

use App\Models\Appointment;

class AppointmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Appointment instance.
     *
     * @var \App\Models\Appointment
     */
    public $appointment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your appointment has been confirmed!')->view('emails.appointment_approved');
    }
}
