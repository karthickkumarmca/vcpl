<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ URL('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL('css/AdminLTE.min.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        body {
            overflow-y: hidden;
            background-repeat: no-repeat;
            background-size: cover;
            background:#0078bf;
        }

        .login-box {
            margin: 5% 0% !important;
            box-shadow: 1px 1px 5px 5px #efefef;
        }

        .login-box-body {
            background-color: #fff !important;
        }

        .g-recaptcha {
            transform-origin: left top;
            -webkit-transform-origin: left top;
        }

        .login-logo {
            margin-top: 30% !important;
            margin-bottom: 0% !important;
            background: #fff;
            font-weight: 800;
            
        }
        #recaptcha_switch_audio { display: none; }
        .container-fluid{
            width: 85%;margin-left: 38%;
        }
    </style>
</head>

<body class="hold-transition" style="height: auto !important">
    <div class="container-fluid">
        <div class="login-box">
            <div class="login-logo">
                <a href="/">
                    <span class="logo-lg">
                        <img src="images/logonew.png">
                    </span>
                </a>
            </div>
            <div class="login-box-body">
                <h3 class="login-box-msg">Sign In</h3>
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <p class="login-box-msg text-danger text-center">{{$error}}</p>
                @endforeach
                @endif
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group has-feedback @if ($errors->has('email')) has-error @endif col-md-12">
                            <label>Username <span class="text-danger"> *</span></label>
                            <input type="text" name="email" class="form-control" placeholder="Enter user name">
                        </div>
                        <div class="form-group has-feedback @if ($errors->has('password')) has-error @endif col-md-12">
                            <label>Password <span class="text-danger"> *</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password">
                        </div>
                        <div class="form-group has-feedback @if ($errors->has('g-recaptcha-response')) has-error @endif col-md-12" id="recaptcha-box" style="max-width:100%;">
                            <div id="capcha-element"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block btn-flat" style="background-color: #a0ba0a;">
                                <strong>Sign In</strong>
                            </button>
                        </div>
                    </div>
                </form>
                <br />
                <!-- <a href="#">I forgot my password</a><br> -->
            </div>
            @error('login-error')
            <span class="help-block has-error" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <script src="{{ URL('js/jquery.min.js') }}"></script>
    <script src="{{ URL('js/bootstrap.min.js') }}"></script>
   
</body>

</html>