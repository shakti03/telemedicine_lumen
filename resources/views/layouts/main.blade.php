<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Telemedicine</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    {{-- <link rel="icon" type="image/x-icon" href="favicon.ico"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=block" rel="stylesheet">


    <script>
        var baseUrl = '{{ URL::to('/') }}';
        var ua = navigator.userAgent;
        var device = "desktop";
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile|CriOS/i.test(ua))
            device = "mobile";

        console.log(device)

    </script>
    @yield('header_styles')

<body class="mat-typography">
    @yield('content')

    @yield('footer_scripts')

</html>
