<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trust Dom</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-panel.css')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- endinject -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front-responsive-style.css') }}">
    <!-- End layout styles -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- end plugin js for this page -->
</head>

  <body>
         @yield('content')
  </body>
</html>