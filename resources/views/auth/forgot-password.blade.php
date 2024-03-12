<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forgot Password</title>
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
    ul {
        list-style-type: none;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- forgot password  -->
    <!-- ============================================================== -->
    <div class="splash-container" style="max-width: 500px !important;">
        <div class="row">
            <div class="col-12">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> {{ session('message') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header text-center">
                <img class="logo-img" src="../assets/images/logo.png" alt="logo">
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <p>Don't worry, we'll send you an email to reset your password.</p>
                    <div class="form-group">
                        <x-text-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus />
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="text-danger mt-1">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-block btn-xl" value="Reset Password">
                    </div>
                </form>
            </div>
          
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end forgot password  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
</body>

 
</html>