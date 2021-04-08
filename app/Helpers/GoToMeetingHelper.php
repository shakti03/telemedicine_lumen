<?php

namespace App\Helpers;

use App\Models\GoToMeeting;

use App\Services\GoToMeeting\GoToClient;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class GoToMeetingHelper
{
    protected $logger;
    protected $goToClient;

    public function __construct()
    {
        $this->logger = Log::channel('gotomeeting');
        $this->goToClient = new GoToClient();
    }


    /**
     * Create GoToMeeting
     */
    public function createGotoMeeting($appointment)
    {
        $gotoClient = $this->goToClient;

        $meetingStartDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        $meetingDuration = $appointment->duration && $appointment->duration <= 60 ? $appointment->duration : 30;

        $meeting = null;
        $gotoMeeting = null;
        try {
            $this->logger->debug("--- Creating GoToMeeting Link for " . $appointment->patient_name);
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

                return $gotoMeeting;
            }
        } catch (\Exception $ex) {
            $this->logger->debug($ex->getMessage());
        }

        return null;
    }
}
