<?php

namespace App\Http\Controllers\Physician;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\Appointment;
use App\Models\MeetingSchedule;
use App\Models\MeetingQuestion;
use App\Models\GoToMeeting;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentRejected;

use App\Services\GoToMeeting\GoToClient;

use Illuminate\Support\Facades\Mail;
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
     * Get Meeting Setting
     *
     * @return JSON
     */
    public function getAppointmentSetting(Request $request)
    {
        $user = $request->user();

        $meeting = $user->meeting()->with('schedules', 'questions')->first();

        return response()->json($meeting);
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateAppointmentSetting(Request $request)
    {
        $user = $request->user();
        $meeting = $user->meeting;

        if ($request->action == 'update_duration') {
            $this->validate($request, [
                'meeting_duration' => 'required'
            ]);
            $meeting->meeting_duration = $request->meeting_duration;
            $meeting->save();

            return response()->json(['data' => $meeting, 'message' => 'Meeting duration updated successfully!']);
        } else if ($request->action == "payment_detail") {
            $this->validate($request, [
                'physician_fee' => 'required'
            ]);
            $meeting->accept_payment = $request->physician_fee ? 1 : 0;
            $meeting->physician_fee = $request->physician_fee;
            $meeting->save();

            return response()->json(['data' => $meeting, 'message' => 'Payment detail updated successfully!']);
        } {
            $this->validate($request, [
                'title' => 'required',
                'location' => 'required'
            ]);

            $meeting->title = $request->title;
            $meeting->location = $request->location;
            $meeting->description = $request->description;
            $meeting->save();

            return response()->json(['data' => $meeting, 'message' => 'Appointment Detail updated!']);
        }
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateSchedules(Request $request)
    {
        $user = $request->user();
        $meeting = $user->meeting;

        $inputSchedules = $request->schedules;

        $dates = array_column($inputSchedules, 'date');
        $meeting->schedules()->whereIn('date', $dates)->delete();

        foreach ($inputSchedules as $inputSchedule) {
            if (isset($inputSchedule['id'])) {
                $schedule = MeetingSchedule::find($inputSchedule['id']);
            } else {
                $schedule = new MeetingSchedule();
                $schedule->meeting_id = $meeting->id;
                $schedule->date = $inputSchedule['date'];
            }

            $schedule->title = $inputSchedule['title'];
            $schedule->start_time = $inputSchedule['start_time'];
            $schedule->end_time = $inputSchedule['end_time'];
            $schedule->save();
        }

        return response()->json([
            'data' => $meeting->schedules,
            'message' => 'Schedules updated successfully.'
        ]);
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateQuestions(Request $request)
    {
        $user = $request->user();
        $meeting = $user->meeting;

        $inputQuestions = $request->questions;

        $meeting->questions()->delete();


        foreach ($inputQuestions as $inputQuestion) {
            if (isset($inputQuestion['id'])) {
                $question = MeetingQuestion::find($inputQuestion['id']);
            } else {
                $question = new MeetingQuestion();
                $question->meeting_id = $meeting->id;
            }

            $question->title = $inputQuestion['title'];
            $question->save();
        }

        return response()->json([
            'data' => $meeting->questions,
            'message' => 'Invitee questions updated successfully.'
        ]);
    }

    /**
     * Get Appointments
     */
    public function getAppointments(Request $request)
    {
        $user = $request->user();
        $meeting = $user->meeting;

        if (!$meeting) {
            return response()->json(['message' => 'Meeting does not exist']);
        }

        $appointments = $meeting->appointments()
            ->with('questions:appointment_id,question as title,answer')
            ->with('gotomeeting:appointment_id,join_url')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();
        $past = [];
        $upcoming = [];

        $now = Carbon::now()->setTimezone($request->timezone)->format('Y-m-d H:i');
        foreach ($appointments as $appointment) {
            $dateTime = $appointment->appointment_date . ' ' . $appointment->appointment_time;
            if ($dateTime >= $now) {
                $appointment->appointment_datetime = $dateTime;
                $upcoming[] = $appointment;
            } else {
                $appointment->appointment_datetime = $dateTime;
                $past[] = $appointment;
            }
        }

        return response()->json([
            'past' => $past,
            'upcoming' => $upcoming
        ]);
    }

    /**
     * Change Appointment Status
     */
    public function changeAppointmentStatus(Request $request, $appointmentId)
    {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $appointment = Appointment::whereUuid($appointmentId)->first();
        if (!$appointment) {
            return response()->json(['message' => 'Appointment does not exist'], 404);
        }

        try {

            $gotoClient = new GoToClient();

            $meetingStartDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
            $meetingDuration = $appointment->duration && $appointment->duration <= 60 ? $appointment->duration : 30;

            $meeting = null;
            $gotoMeeting = null;
            try {
                $payload = [
                    "subject" => "Meeting with " . $appointment->patient_name,
                    "starttime" => $meetingStartDateTime->format('Y-m-d\TH:i:s\Z'), // "2021-06-12T12:00:00Z",
                    "endtime" => $meetingStartDateTime->addMinutes($meetingDuration)->format('Y-m-d\TH:i:s\Z'), //"2021-06-12T13:00:00Z",
                    "passwordrequired" => false,
                    "conferencecallinfo" => "VoIP",
                    "timezonekey" => "",
                    "meetingtype" => "scheduled"
                ];

                $gotoMeetingResponse = $gotoClient->createMeeting($payload);

                if ($gotoMeetingResponse) {
                    $gotoMeeting = new GoToMeeting;
                    $gotoMeeting->subject = $payload['subject'];
                    $gotoMeeting->starttime = $payload['starttime'];
                    $gotoMeeting->endtime = $payload['endtime'];
                    $gotoMeeting->appointment_id = $appointment->id;
                    $gotoMeeting->goto_meetingid = $gotoMeetingResponse['uniqueMeetingId'];
                    $gotoMeeting->join_url = $gotoMeetingResponse['joinURL'];
                    $gotoMeeting->other = json_encode($gotoMeetingResponse);
                    $gotoMeeting->save();
                }
            } catch (\Exception $ex) {
                Log::channel('gotomeeting')->debug($ex->getMessage());
            }

            if (!$gotoMeeting) {
                return response()->json(['message' => 'Unable to create meeting link.'], 400);
            }


            if ($request->status == 1) {
                Mail::to($appointment->patient_email)->send(new AppointmentApproved($appointment, $gotoMeeting));
            } else if ($request->status == 2) {
                Mail::to($appointment->patient_email)->send(new AppointmentRejected($appointment));
            }

            $appointment->status = Appointment::statuses[$request->status];
            $appointment->save();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to send approval email.'], 400);
        }

        return response()->json(['message' => 'Appointment status updated']);
    }
}
