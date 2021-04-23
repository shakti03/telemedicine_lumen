<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TeleMedicine | Inite</title>

    <style>
        .t-title {
            width: 70px;
        }

    </style>
</head>

<body>
    <div>
        <p>Hello,</p>

        <p>Here is the link where you can book an appointment with <strong>{{ $user->full_name }}</strong></p>
        <a href="{{ $link }}">Visit Link</a>

        <br> <br>
        <div>
            Regards <br>
            <address>
                TeleMedicine
            </address>
        </div>
    </div>
</body>

</html>
