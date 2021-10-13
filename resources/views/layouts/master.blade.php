<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/simple-datatables/style.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/Chart.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    <link rel="stylesheet" href="{{ asset('jquery-datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('jquery-datatable/buttons.bootstrap4.min.css') }}">
   <!------------ chart start --------------------->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
 <!-------------- chart end --------------------->
   
</head>

<body>

    <div id="app">
        <div id="sidebar" class="active">
            @include('layouts.sidebar')
        </div>

        <div id="main" class='layout-navbar'>
            @include('layouts.header')

            <div id="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('jquery-datatable/jquery.min.js') }}"></script>
    <script src="{{ asset('perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script> --}}
    <script src="{{ asset('js/excel.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    {{-- <script src="{{ asset('js/apexcharts.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/dashboard.js') }}"></script> --}}

    {{-- <script src="{{ asset('css/simple-datatables/simple-datatables.js') }}"></script> --}}
    <script src="{{ asset('jquery-datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('jquery-datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('jquery-datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('jquery-datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('jquery-datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('jquery-datatable/buttons.html5.min.js') }}"></script>

    {{-- <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/ui-chartjs.js') }}"></script> --}}
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('script')

</body>
</html>
