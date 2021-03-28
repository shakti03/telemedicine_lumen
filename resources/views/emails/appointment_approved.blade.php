<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TeleMedicine | Appointment Approved</title>

    <style>
        .t-title {
            width: 70px;
        }
    </style>
</head>
<body>
    <p>Hello {{ $appointment->patient_name }},</p>

    <p>Your appointment request has been approved!</p>

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

    @if(isset($appointment->payment))
    <p>
        Please complete the payment to confirm the appointment. Here is the link<br>
        <a href="{{$appointment->payment->payment_link}}">Payment Now</a>

        {{ $appointment->payment->payment_link }}
    </p>
    @endif

    @if(isset($gotoMeeting))
    <p>
        <a href="{{ $gotoMeeting->join_url }}">Join Meeting</a>
    </p>
    @endif

    <br> <br>
    <p>
        Regards <br>
        <address>TeleMedicine</address>
    </p>
</body>
</html>