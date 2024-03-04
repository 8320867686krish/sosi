<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">

    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container" style="max-width: 500px !important;">
        <div class="row">
            <div class="col-12">
                @include('layouts.message')
            </div>
        </div>
        <div class="card">
            <div class="card-header text-center"><a href="#"><img class="logo-img" src="{{ asset('assets/images/logo.png') }}" alt="logo"></a></div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" autofocus autocomplete="username" placeholder="Email" />
                        @if($errors->has('email'))
                            <div class="error text-danger mt-1">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <x-input-label for="password" :value="__('Password')" />
                        <input class="form-control form-control-lg" id="password" name="password" type="password" placeholder="Password">
                        @if($errors->has('password'))
                            <div class="error text-danger mt-3">{{ $errors->first('password') }}</div>
                        @endif
                        @if($errors->has('autherror'))
                            <div class="error text-danger mt-3">{{ $errors->first('autherror') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered float-right">
                    <a href="{{ route('password.request') }}" class="footer-link">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
<script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>

</body>

</html>
