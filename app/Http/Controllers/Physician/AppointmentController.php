<?php

namespace App\Http\Controllers\Physician;

use App\Models\Appointment;
use App\Models\MeetingSchedule;
use App\Models\MeetingQuestion;
use App\Models\Meeting;

use App\Mail\AppointmentApproved;
use App\Mail\AppointmentRejected;

use App\Helpers\PaymentHelper;
use App\Helpers\GoToMeetingHelper;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

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

        if ($request->type == 'upcoming')
            return $this->upcomingAppointments($meeting, $request);

        $appointments = $meeting->appointments()
            ->with('questions:appointment_id,question as title,answer')
            ->with('gotomeeting:appointment_id,join_url')
            ->orderBy(DB::raw('FIELD(status, "PENDING")'), 'DESC')
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
            'upcoming' => $past
        ]);
    }

    /**
     * Get Upcoming appointments
     */
    private function upcomingAppointments(Meeting $meeting, Request $request)
    {
        $now = Carbon::now()->setTimezone($request->timezone)->format('Y-m-d H:i:s');

        $appointments = $meeting->appointments()
            ->with('questions:appointment_id,question as title,answer')
            ->with('gotomeeting:appointment_id,join_url')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->where(function ($q) {
                $q->where('payment_status', 'PAID')
                    ->orWhere(function ($sq) {
                        $sq->where('status', 'APPROVED')
                            ->where(function ($sq2) {
                                $sq2->where('fee', '0.00')
                                    ->orWhere('meeting_location', 'phone');
                            });
                    });
            })
            ->whereRaw("CONCAT(appointment_date, ' ', appointment_time) >= '" . $now . "'")
            ->select(DB::raw("*, CONCAT(appointment_date, ' ', appointment_time) as appointment_datetime"))
            ->limit(5)
            ->get();


        return response()->json([
            'upcoming' => $appointments
        ]);
    }

    /**
     * Patient in queue
     */
    public function waitingAppointments(Request $request)
    {
        $carbonNow = Carbon::now()->setTimezone($request->timezone);
        $now = $carbonNow->format('Y-m-d H:i:s');
        $fromTime = $carbonNow->subMinutes(15)->format('Y-m-d H:i:s');
        $user = $request->user();
        $meeting = $user->meeting;

        if (!$meeting) {
            return response()->json(['message' => 'Meeting does not exist']);
        }

        $appointments = $meeting->appointments()
            ->with('gotomeeting:appointment_id,join_url')
            ->where(function ($q) {
                $q->where('payment_status', 'PAID')
                    ->orWhere(function ($sq) {
                        $sq->where('status', 'APPROVED')
                            ->where(function ($sq2) {
                                $sq2->where('fee', '0.00')
                                    ->orWhere('meeting_location', 'phone');
                            });
                    });
            })
            ->whereRaw("CONCAT(appointment_date, ' ', appointment_time) between '" . $fromTime . "' and '" . $now . "'")
            // ->whereRaw("(CONCAT(appointment_date, ' ', appointment_time) + INTERVAL 15 MINUTE) >= '" . $now . "'")
            ->orderBy(DB::Raw("CONCAT(appointment_date, ' ', appointment_time)"))
            ->select(DB::raw("id,uuid, patient_name, meeting_id, CONCAT(appointment_date, ' ', appointment_time) as appointment_datetime"))
            ->get();


        foreach ($appointments as $appointment) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $appointment->appointment_datetime, $request->timezone);
            $appointment->waiting_time = $date->diffForHumans();
        }

        return response()->json($appointments);
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

        //$meeting = $appointment->meeting;

        try {
            if ($request->status == 1) {
                $paymentRequest = null;
                $gotoMeeting = null;

                // Create Payment Link
                if ($appointment->fee > 0) {
                    $payment = new PaymentHelper;
                    $paymentRequest = $payment->createOrder([
                        'appointment_id' => $appointment->id,
                        'amount' => (string) $appointment->fee
                    ]);

                    if (!$paymentRequest) {
                        return response()->json(['message' => 'Unable to create payment link'], 400);
                    }

                    $appointment->payment_status = Appointment::PAYMENT_PENDING;
                    $appointment->save();
                } elseif ($appointment->meeting_location == Meeting::LOCATION_GOTOMEETING) {
                    // Create GoToMeeting Link
                    $gotoHelper = new GoToMeetingHelper;
                    $gotoMeeting = $gotoHelper->createGotoMeeting($appointment);

                    if (!$gotoMeeting) {
                        return response()->json(['message' => 'Unable to create meeting link.'], 400);
                    }
                }

                Mail::to($appointment->patient_email)->send(new AppointmentApproved($appointment, $paymentRequest, $gotoMeeting));
            } else if ($request->status == 2) {
                Mail::to($appointment->patient_email)->send(new AppointmentRejected($appointment));
            }

            $appointment->status = Appointment::statuses[$request->status];
            $appointment->save();

            return response()->json(['message' => 'Appointment status updated']);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'Unable to send approval email.'], 400);
        }
    }

    /**
     * Get Statistical data for appointments
     */
    public function getAppointmentStats(Request $request)
    {
        $user = $request->user();

        // Analytics: Old And New Appointments in last 6 months
        $stats = $user->appointmentStats()
            ->whereRaw('stat_date > now() - INTERVAL 6 MONTH')
            ->selectRaw('DATE_FORMAT(stat_date, "%M-%Y") as month_year, old_patients_count, new_patients_count')
            ->get();

        $now = Carbon::now();
        $oldPatientAppointments = [];
        $newPatientAppointments = [];
        for ($i = 0; $i < 6; $i++) {
            $oldPatientAppointments[$now->format('F-Y')] = 0;
            $newPatientAppointments[$now->format('F-Y')] = 0;
            $now = $now->subMonth();
        }

        foreach ($stats as $stat) {
            if ($stat->old_patients_count)
                $oldPatientAppointments[$stat->month_year] = $stat->old_patients_count;

            if ($stat->new_patients_count)
                $newPatientAppointments[$stat->month_year] = $stat->new_patients_count;
        }

        // Analytics: New Patients in last 6 months 
        $patients = Patient::whereRaw('created_at > (now() - INTERVAL 6 MONTH)')
            ->selectRaw('DATE_FORMAT(created_at, "%M-%Y") as month_year, count(id) as patient_count')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%M-%Y")'))
            ->get();

        $now = Carbon::now();
        $newPatients = [];
        for ($i = 0; $i < 6; $i++) {
            $newPatients[$now->format('F-Y')] = 0;
            $now = $now->subMonth();
        }

        foreach ($patients as $patientStat) {
            if ($patientStat->patient_count)
                $newPatients[$patientStat->month_year] = $patientStat->patient_count;
        }


        return response()->json([
            'appointments' => [
                'labels' => array_keys($oldPatientAppointments),
                'old' => array_values($oldPatientAppointments),
                'new' => array_values($newPatientAppointments)
            ],
            'patients' => [
                'labels' => array_keys($newPatients),
                'new' => array_values($newPatients)
            ]
        ]);
    }

    public function getTotalEarnings(Request $request)
    {
        $total = Payment::join('appointments', 'appointments.id', 'payments.appointment_id')
            ->join('meetings', 'appointments.meeting_id', 'meetings.id')
            ->selectRaw('SUM(amount) as total')
            ->whereRaw("MONTHNAME(payments.created_at) = MONTHNAME(now())")
            ->where('meetings.user_id', $request->user()->id)
            ->first();

        return response()->json($total && $total->total ? $total : ['total' => '0.00']);
    }
}
