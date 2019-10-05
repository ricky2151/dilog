<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dilog</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png', true) }}">
    <meta name="theme-color" content="#F5BF0E">
    <meta name="description" content="Warehousing App">
    <meta name="author" content="Dilog">
    <link rel="stylesheet" href="{{ asset(mix('/css/app.css'), true) }}">
</head>
<body>
    <div id="app">
        <dilog-app></dilog-app>
    </div>
  <script src="{{ asset(mix('/js/app.js'), true) }}"></script>
</body>
</html>
