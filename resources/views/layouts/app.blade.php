@include('layouts.front-meta')
    <!-- Scripts -->
    <!--dashboard -->
    <!-- <link rel="stylesheet" href="{{asset('vendors/core/core.css')}}"> -->
    <!-- <link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}"> -->
    <!-- <link rel="stylesheet" href="{{asset('vendors/flag-icon-css/css/flag-icon.min.css')}}"> -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin-style.css')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&family=Poppins:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- endinject -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front-responsive-style.css') }}">
    <!-- End layout styles -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <!-- <script src="{{asset('js/custom.js')}}"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <!-- end plugin js for this page -->
    <!-- inject:js -->
</head>
<body>
    @if(session()->has('message'))
        <div class="container alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    @yield('content')
            
    </div>
    <!--dashboard -->
    <!-- <script src="{{asset('vendors/core/core.js')}}"></script> -->
    <!-- endinject -->
    <!-- plugin js for this page -->
<!--     <script src="{{asset('vendors/chartjs/Chart.min.js')}}"></script>
    <script src="{{asset('vendors/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{asset('vendors/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('vendors/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('vendors/progressbar.js/progressbar.min.js')}}"></script> -->
    <script src="{{asset('js/dashboard.js')}}"></script>
    <!-- <script src="{{asset('js/data-table.js')}}"></script> -->
    <!-- end custom js for this page -->
    <!-- inject:js -->
    <script src="{{asset('js/template.js')}}"></script>

    
</body>
</html>
