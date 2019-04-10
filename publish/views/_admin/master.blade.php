<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content={{csrf_token()}}>

    <title>Laravel</title>
    {{-- <link rel="stylesheet" href="/css/app.css"></link> --}}
    <link rel="stylesheet" href="/dist/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/dist/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/dist/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/dist/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/dist/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/dist/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/plugins/fancybox/jquery.fancybox.css') }}" media="screen" />
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/plugins/fancybox/helpers/jquery.fancybox-buttons.css') }}" media="screen" />
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- IonIcons -->
    {{-- <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
    <link rel="stylesheet" href="/css/main.css">
    @yield('header')
</head>

<body class="hold-transition sidebar-mini">
    @guest @yield('content') @else
    <div class="wrapper" id="app">
        <!-- Header -->
    @include('_admin.header')
        <!-- Sidebar -->
    @include('_admin.sidebar') 
        <div class="content-wrapper">
            @if (Session::has('success') || Session::has('error') || Session::has('warning'))
                <div class="px-3 pt-3">
                    <div class="alert @if (Session::has('success')) alert-success @elseif(Session::has('error')) alert-danger @else alert-warning @endif alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @if (Session::has('success'))
                            <h5><i class="icon fa fa-check"></i>{!! Session::get('success') !!} </h5>
                        @elseif(Session::has('error'))
                            <h5><i class="icon fa fa-ban"></i>{!! Session::get('error') !!} </h5>
                        @else
                            <h5><i class="icon fa fa-warning"></i>{!!  Session::get('warning') !!} </h5>
                        @endif
                    </div>
                </div>
            @endif
            @if (Session::has('message'))
                <div class="px-3 pt-3">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <h5>{!! Session::get('message') !!}</h5>
                    </div>
                </div>
            @endif
            <div id="alert-msg"></div>
            @yield('content')
        </div>
        <!-- Footer -->
    @include('_admin.footer')
    </div>
    <!-- ./wrapper -->
    @endguest 
    <!-- jQuery -->
    <script type="text/javascript">
        var BASE_URL  = '{{ url('/') }}';
    </script> 
    <script src="/dist/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dist/plugins/fancybox/jquery.fancybox.pack.js') }}"></script>
    <script src="{{ asset('dist/plugins/fancybox/helpers/jquery.fancybox-buttons.js') }}"></script>
    <script src="{{ asset('dist/plugins/fancybox/helpers/jquery.fancybox-media.js') }}"></script>
    <script src="{{ asset('dist/plugins/ckeditor/ckeditor.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.js"></script>
    <script src="/dist/js/admin.js"></script>
    
    @yield('javascript')
    <script type="text/javascript">
        $('.alert').alert()
    </script>
</body>
</html>