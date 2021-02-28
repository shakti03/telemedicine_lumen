<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Appointment;


class AppointmentRejected extends Mailable
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
        return $this->subject('Your appointment request has been rejected!')->view('emails.appointment_rejected');
    }
}
