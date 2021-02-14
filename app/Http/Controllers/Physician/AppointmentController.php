<?php

namespace App\Http\Controllers\Physician;

use Illuminate\Http\Request;
use App\Models\MeetingSchedule;
use App\Models\MeetingQuestion;

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
     * Get Profile
     *
     * @return JSON
     */
    public function getAppointmentDetail(Request $request)
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
    public function updateAppointmentInfo(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'location' => 'required'
        ]);

        $user = $request->user();
        $meeting = $user->meeting;

        $meeting->name = $request->name;
        $meeting->location = $request->location;
        $meeting->description = $request->description;
        $meeting->save();

        return response()->json($meeting);
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateSchedules(Request $request)
    {
        $meeting = $request->user->meeting;

        $inputSchedules = $request->schedules;

        foreach ($inputSchedules as $inputSchedule) {
            if ($inputSchedule['id']) {
                $schedule = MeetingSchedule::find($inputSchedule['id']);
            } else {
                $schedule = new MeetingSchedule();
                $schedule->date = $inputSchedule['date'];
            }

            $schedule->start_time = $inputSchedule['start_time'];
            $schedule->end_time = $inputSchedule['end_time'];
            $schedule->save();
        }

        return response()->json($meeting->schedules);
    }

    /**
     * Update Profile
     *
     * @return JSON
     */
    public function updateQuestions(Request $request)
    {
        $meeting = $request->user->meeting;

        $inputSchedules = $request->schedules;

        foreach ($inputSchedules as $inputSchedule) {
            if ($inputSchedule['id']) {
                $schedule = MeetingQuestion::find($inputSchedule['id']);
            } else {
                $schedule = new MeetingQuestion();
            }

            $schedule->start_time = $inputSchedule['start_time'];
            $schedule->end_time = $inputSchedule['end_time'];
            $schedule->save();
        }

        return response()->json($meeting->questions);
    }
}
