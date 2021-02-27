<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\AppointmentAnswer;
use App\Models\Meeting;
use App\Models\User;

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

    /**
     * Get Physician Meeting Detail
     * @param $roomName string;
     * @return JSON
     */
    public function getPhysicianMeetingDetail(Request $request, $physicianLink)
    {
        $physician = User::where('room_name', $physicianLink)->first();

        if (!$physician) {
            return response()->json(['message' => 'physician not found'], 404);
        }

        $meeting = $physician->meeting()->with(['schedules' => function ($query) {
            $query->where('date', '>=', date('Y-m-d'));
        }, 'questions'])->first();

        return response()->json($meeting);
    }

    /**
     * Create Patient Appointment
     * @return 
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'meeting_id' => 'required',
            'appointment_date' => 'required',
            'appointment_time' => 'required',
            'name' => 'required',
            'email' => 'required',
            // 'phone' => 'required',
            'symptoms' => 'required',
            'description' => 'required'
        ]);

        $meeting = Meeting::where('uuid', $request->meeting_id)->first();

        if (!$meeting) {
            return response()->json(['message' => 'Meeting does not exist']);
        }

        $appointment = new Appointment();
        $appointment->meeting_id = $meeting->id;
        $appointment->patient_name = $request->name;
        $appointment->patient_email = $request->email;
        $appointment->phone = $request->phone;
        $appointment->symptoms = $request->symptoms;
        $appointment->summary = $request->description;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->duration = $meeting->meeting_duration;
        $appointment->fee = $meeting->physician_fee;
        $appointment->save();

        foreach ($request->questions as $question) {
            $appointmentAnswer = new AppointmentAnswer();
            $appointmentAnswer->appointment_id = $appointment->id;
            $appointmentAnswer->question = $question['question'];
            $appointmentAnswer->answer = $question['answer'];
            $appointmentAnswer->save();
        }

        return response()->json(['data' => [
            'title' => $meeting->title,
            'physician_name' => $meeting->user->full_name,
            'appointment_date' => $appointment->appointment_date,
            'appointment_time' => $appointment->appointment_time,
            'appointment_duration' => $appointment->duration
        ], 'message' => 'Appointment created successfully']);

        // $paymentHelper = new PaymentHelper();
        // $result = $paymentHelper->createOrder([
        //     'appointment_id' => $appointment->id,
        //     'amount' => $appointment->fee
        // ]);

        // if ($result && $result->status == PaymentHelper::SUCCESS) {
        //     DB::commit();
        //     return response()->json([
        //         'payment_link' => $result->payment_link
        //     ]);
        // } else {
        //     DB::rollBack();
        //     return response()->json([
        //         'message' => $result ? $result->error : 'Unable to complete the payment'
        //     ], 400);
        // }
        // } catch (\Exception $ex) {
        //     DB::rollBack();
        //     Log::error($ex);
        //     return response()->json([
        //         'message' => $result ? $result->error : 'Unable to complete the payment'
        //     ], 400);
        // }
    }
}
