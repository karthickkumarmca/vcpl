<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME'); }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <!-- <link rel="icon" href="{{ asset('images/fav-icon.png') }}" type="image/png" sizes="32x32"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ URL('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/skin-blue.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="{{ URL('css/custom/dataTable.css') }}">
    <link rel="stylesheet" href="{{ URL('css/custom/formValidation.css') }}">
    <link rel="stylesheet" href="{{ URL('css/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/custom/style.css') }}">
    <link rel="stylesheet" href="{{ URL('css/plugins/multiselect/jquery.multiselect.css') }}">
    @stack('styles')
</head>

<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
        @include('layouts.header')
        @include('layouts.sidebar-menu')
        <div class="content-wrapper">
            @yield('content')
            <div class="corona-preloader-backdrop">
                <div class="corona-page-preloader">Loading...</div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
    <script src="{{ URL('js/jquery.validate.min.js') }}"></script>   
    @yield('before-scripts-end')
    @include('layouts.scripts')
    @stack('scripts')
    @yield('after-scripts-end')
</body>

</html>