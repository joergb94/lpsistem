<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sufee Admin - HTML5 Admin Template</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/jqvmap/dist/jqvmap.min.css') }}">

    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>
<div id="appwraper" style="display:none">
    <!-- Left Panel -->
    @guest
        <!-- Right Panel -->
        <div  id="app"  class="right-panel-login" >
    @else
        @include('layouts.items.leftPanel')

    <!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->
        <div  id="app"  class="right-panel">
    @endguest
   
        
        <!-- Header-->
            @include('layouts.items.header')
        <!-- /header -->
        <!-- Header-->
            <div  class="content mt-3" style="width:100%;">
                <div class="col-sm-12">
                    @yield('content')
                </div>
                <!--/.col-->
            </div> 
        </div> 
        <!-- .content -->
    </div>
        <div id="loading-wrapper" style=" padding-top: 200px;" class="col-sm-12 text-center">
        <img src="{{asset('images/load.svg')}}">
            <h1>Cargando ...</h1>
        </div>

    <!-- /#right-panel -->
    <!-- Right Panel -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    @guest
    @else
        @if($dm['type_user']==5)
            <script src="{{ asset('js/complement.js') }}"></script>
        @endif
    @endguest
    @yield('js')
</body>

</html>
