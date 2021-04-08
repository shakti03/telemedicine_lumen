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
    <div>
        <p>Hello {{ $appointment->patient_name }},</p>

        <p>Your appointment request has been approved!</p>

        <div>
            <p>
                <strong class="t-title">Event</strong>
                <span>: {{ $appointment->meeting->title }}</span>
            </p>

            <p>
                <strong class="t-title">Name</strong>
                <span>: {{ $appointment->patient_name }}</span>
            </p>

            <p>
                <strong class="t-title">Date</strong>
                <span>: {{ $appointment->appointment_date }}</span>
            </p>

            <p>
                <strong class="t-title">Time</strong>
                <span>:
                    {{ date('H:i A', strtotime($appointment->appointment_date . ' ' . $appointment->appointment_time)) }}</span>
            </p>

            <p>
                <strong class="t-title">Meeting Type</strong>
                <span>:
                    {{ $appointment->meeting_location == \App\Models\Meeting::LOCATION_GOTOMEETING ? 'GoToMeeting' : 'Phone' }}</span>
            </p>

            @if (isset($payment))
                <p>
                    Please complete the payment to confirm the appointment. Here is the link<br>
                    <a href="{{ $payment->payment_link }}">Payment Now</a>
                </p>
            @elseif(isset($gotoMeeting))
                <p>
                    Please click the below link to join the meeting <br />
                    <a href="{{ $gotoMeeting->join_url }}">Join Meeting</a>
                </p>
            @endif
        </div>

        <br> <br>
        <div>
            Regards <br>
            <address>TeleMedicine</address>
        </div>
    </div>
</body>

</html>
