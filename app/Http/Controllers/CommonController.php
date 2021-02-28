<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApproved;
use App\Mail\AppointmentRejected;
use App\Models\Appointment;

class CommonController extends Controller
{
    public function getSymptoms()
    {
        return response()->json([
            ['name' => 'Fever'],
            ['name' => 'Cough'],
            ['name' => 'BackPain'],
            ['name' => 'Fatigue']
        ]);
    }

    public function sendEmail(Request $request)
    {
        $appointment = Appointment::first();

        try {
            Mail::to('shaktisingh03@gmail.com')->send(new AppointmentRejected($appointment));
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
