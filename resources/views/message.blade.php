<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Telemedicine</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#ffffff">

  
  <style>
      .text-center {
          text-align: center
      }
      .top-center {
          width: 100%;
          position: absolute;
          top: 30%;
      }
  </style>
</head> 
<body class="text-center">
    <div class="top-center">
        <h1 class="mt-5"> {{ $message }} </h1>
        @if(isset($link))
        <a href="{{ URL::to($link['url']) }}">{{ $link['label'] }}</a>
        @endif
    </div>
</body>
</html>
