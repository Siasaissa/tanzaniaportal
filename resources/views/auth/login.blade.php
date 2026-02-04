<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('login_asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/hamburgers.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/animsition.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/util.css') }}">
    <link rel="stylesheet" href="{{ asset('login_asset/css/main.css') }}">
</head>

<body>

<div class="limiter">
    <div class="container-login100" style="background-image: url('{{ asset('login_asset/images/bg-01.jpg') }}');">
        <div class="wrap-login100">

            <!-- Laravel Login Form -->
            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                @csrf

                <span class="login100-form-logo">
                    <i class="zmdi zmdi-landscape"></i>
                </span>

                <span class="login100-form-title p-b-34 p-t-27">
                    Welcome back <br> Admin
                </span>

                <!-- Email -->
                <div class="wrap-input100 validate-input" data-validate="Enter email">
                    <input class="input100" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                    <span class="focus-input100" data-placeholder=""></span>
                </div>

                <!-- Password -->
                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input class="input100" type="password" name="password" required placeholder="Password">
                    <span class="focus-input100" data-placeholder=""></span>
                </div>

                <!-- Remember Me -->
                <div class="contact100-form-checkbox">
                    <input class="input-checkbox100" id="remember_me" type="checkbox" name="remember">
                    <label class="label-checkbox100" for="remember_me">
                        Remember me
                    </label>
                </div>

                <!-- Submit -->
                <div class="container-login100-form-btn">
                    <button type="submit" class="login100-form-btn">
                        Login
                    </button>
                </div>

                <!-- 
                <div class="text-center p-t-90">
                    @if (Route::has('password.request'))
                        <a class="txt1" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                Forgot Password -->
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/animsition.min.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>
<script src="{{ asset('js/countdowntime.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
