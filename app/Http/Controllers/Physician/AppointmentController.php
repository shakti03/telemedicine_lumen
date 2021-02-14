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
            'title' => 'required',
            'location' => 'required'
        ]);

        $user = $request->user();
        $meeting = $user->meeting;

        $meeting->title = $request->title;
        $meeting->location = $request->location;
        $meeting->description = $request->description;
        $meeting->save();

        return response()->json(['data' => $meeting, 'message' => 'Appointment Detail updated!']);
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
}
