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
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('js/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css') }}">
    <link rel="stylesheet" href="{{asset('css/admin-panel.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-responsive-style.css')}}">
    <!-- end plugin js for this page -->
    
</head>
  <body>
     <div class="main-wrapper">        
       <x-leftsidebar/>
        <!-- partial -->
        <div class="page-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar justify-content-end">
                <div class="navbar-content header-nav-content d-flex align-items-center">
                    <a href="{{url('admin/profile')}}" class="btn">My Profile</a>|
                    <a href="{{url('admin/logout')}}" class="btn btn-outline-primary">Logout</a>
                </div>
            </nav>
            <div class="page-content">
                @if(session()->has('successMsg'))
                    <span class="text-success" role="alert">
                        <strong> {{ session('successMsg') }} </strong>
                    </span>
                @endif 
                @if(session()->has('errorMsg'))
                    <span class="text-danger" role="alert">
                        <strong> {{ session('errorMsg') }} </strong>
                    </span>
                @endif   
                @yield('content')
            </div>
        </div>
    </div>
    <!-- end custom js for this page -->
    <script src="{{asset('js/core/core.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <script src="{{asset('js/script2.js')}}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
   <script src="//cdn.ckeditor.com/4.16.1/full/ckeditor.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>

   <script>
        CKEDITOR.replace( 'ckEditor' );
   </script>

  </body>
</html>