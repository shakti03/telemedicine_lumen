<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Frontend2</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="styles.css"></head>
	@yield('header_styles')
<body class="mat-typography">
	@yield('content')

	@yield('footer_scripts')
</html>
