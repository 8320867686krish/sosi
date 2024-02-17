<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reset Password</title>
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
<!-- ============================================================== -->
<!-- signup form  -->
<!-- ============================================================== -->

<body>
    <!-- ============================================================== -->
    <!-- signup form  -->
    <!-- ============================================================== -->
    
    @if(isset($isExpired) && $isExpired === 1)
        <form class="splash-container" method="POST" action="{{ route('password.store') }}" style="max-width: 500px !important">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-1 text-center">Reset Password</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email', $request->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="form-group">
                        <x-input-label for="password" :value="__('Password')" />
                        <input class="form-control form-control-lg" id="pass1" type="password" required="" placeholder="Password" name="password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="form-group">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <input type="password" class="form-control form-control-lg" name="password_confirmation" required="" placeholder="Confirm Password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        <input type="hidden" name="token" value="{{Request::segment(2) ?? null}}">
                    </div>
                    <div class="form-group pt-2">
                        <button class="btn btn-block btn-primary" type="submit">Reset password</button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="splash-container taxt-danger border shadow">
            <h3>This password reset token is invalid.</h3>
            <a href="{{ route('password.request') }}"><button class="btn btn-block btn-primary" type="button">Back</button></a>
        </div>
    @endif
</body>


</html>