<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dilog</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <meta name="theme-color" content="#F5BF0E">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
</head>
<body>
    <div id="app">
        <dilog-app></dilog-app>
    </div>
  <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
