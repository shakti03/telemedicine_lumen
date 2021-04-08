<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TeleMedicine | Appointment Confirmed</title>

    <style>
        .t-title {
            width: 70px;
        }
    </style>
</head>
<body>
    <p>Hello {{ $appointment->meeting->user->full_name }},</p>

    <p>Payment received for the appointment with {{ $appointment->patient_name }}</i></b>. </p>
    <p> Here is the meeting detail </p>
    <div>
        <p>
            <strong class="t-title">Event</strong>
            <span>: {{ $appointment->meeting->title}}</span>
        </p>
    
        <p>
            <strong class="t-title">Name</strong>
            <span>: {{ $appointment->patient_name}}</span>
        </p>
    
        <p>
            <strong class="t-title">Date</strong>
            <span>: {{ $appointment->appointment_date}}</span>
        </p>
    
        <p>
            <strong class="t-title">Time</strong>
            <span>: {{ date('H:i A', strtotime($appointment->appointment_date . ' ' . $appointment->appointment_time) )}}</span>
        </p>

        <p>
            <strong class="t-title">Meeting Type</strong>
            <span>: {{ $appointment->meeting_location == \App\Models\Meeting::LOCATION_GOTOMEETING ? "GoToMeeting" : "Phone" }}</span>
        </p>
    </div>

    @if(isset($gotoMeeting))
    <p>
        Please click the below link to join the meeting <br/>
        <a href="{{ $gotoMeeting->join_url }}">Join Meeting</a>
    </p>
    @endif
</body>
</html>