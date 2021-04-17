<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\AppointmentApproved;
// use App\Mail\AppointmentRejected;
// use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function getSymptoms(Request $request)
    {
        return response()->json(DB::table('symptoms')->select('name')->where('name', 'LIKE', $request->term . '%')->take(30)->get());
    }
}
