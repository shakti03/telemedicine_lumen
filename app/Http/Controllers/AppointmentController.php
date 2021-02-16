<?php

namespace App\Http\Controllers\Physician;

use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\AppointmentAnswer;
use App\Models\Meeting;

use App\Helpers\PaymentHelper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'meeting_id' => 'required',
            'patient_name' => 'required',
            'patient_email' => 'required',
            'phone' => 'required',
            'symptoms' => 'required',
            'summary' => 'required'
        ]);

        $meeting = Meeting::where('uuid', $request->meeting_id)->first();

        if (!$meeting) {
            return response()->json(['message' => 'Meeting does not exist']);
        }

        DB::beginTransaction();
        try {
            $appointment = new Appointment();
            $appointment->meeting_id = $request->meeting_id;
            $appointment->patient_name = $request->patient_name;
            $appointment->patient_email = $request->patient_email;
            $appointment->phone = $request->phone;
            $appointment->symptoms = $request->symptoms;
            $appointment->summary = $request->summary;
            $appointment->appointment_date = $request->appointment_date;
            $appointment->appointment_time = $request->appointment_time;
            $appointment->fee = $meeting->meeting_fee;
            $appointment->save();

            foreach ($request->questions as $question) {
                $appointmentAnswer = new AppointmentAnswer();
                $appointmentAnswer->appointment_id = $appointment->id;
                $appointmentAnswer->question = $question['question'];
                $appointmentAnswer->answer = $question['answer'];
                $appointmentAnswer->save();
            }

            $paymentHelper = new PaymentHelper();
            $result = $paymentHelper->createOrder([
                'appointment_id' => $appointment->id,
                'amount' => $appointment->fee
            ]);

            if ($result && $result->status == PaymentHelper::SUCCESS) {
                DB::commit();
                return response()->json([
                    'payment_link' => $result->payment_link
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'message' => $result ? $result->error : 'Unable to complete the payment'
                ], 400);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return response()->json([
                'message' => $result ? $result->error : 'Unable to complete the payment'
            ], 400);
        }
    }
}
