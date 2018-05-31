<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __(env('APP_NAME')) }} - @yield('title')</title>

    <!-- Favicon-->
    <link rel="icon" type="image/png" href="{{ asset(env('APP_LOGO')) }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('node_modules/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Waves Effect Css -->
    <link href="{{ asset('node_modules/adminbsb-materialdesign/plugins/node-waves/waves.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Animation Css -->
    <link href="{{ asset('node_modules/adminbsb-materialdesign/plugins/animate-css/animate.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Css -->
    <link href="{{ asset('node_modules/adminbsb-materialdesign/css/style.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    @stack('styles')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="@yield('body_classes')">
    @yield('content')

    <!-- Jquery Core Js -->
    <script type="text/javascript" src="{{ asset('node_modules/adminbsb-materialdesign/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script type="text/javascript" src="{{ asset('node_modules/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- App Js -->
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script type="text/javascript" src="{{ asset('node_modules/adminbsb-materialdesign/plugins/node-waves/waves.min.js') }}"></script>

    <!-- Custom Js -->
    <script type="text/javascript" src="{{ asset('node_modules/adminbsb-materialdesign/js/admin.js') }}"></script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
