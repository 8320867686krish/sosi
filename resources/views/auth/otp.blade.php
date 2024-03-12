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
                <form method="POST" action="#" id="loginForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">



                    <div class="form-group">
                        <x-text-input id="code" class="form-control form-control-lg" type="text" name="code" :value="old('code')" autofocus autocomplete="username" placeholder="Otp" />
                      
                    </div>
                   

                    <button type="button" class="btn btn-primary btn-lg btn-block signIn">Verify</button>
                </form>
            </div>
            
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script>
        $(".signIn").click(function() {
            $.ajax({
                type: "POST",
                url: "{{ url('verify/otp') }}",
                data: $("#loginForm").serialize(),
               
                success: function(response) {
                  if(response.status == true){
                    window.location.href = "{{ url('dashboard') }}";

                  }else{
                    var el = $(document).find('[name=code]');
                            el.after($('<span class="error-message" style="color: red;">' +
                               response.message + '</span>'));
                  }

                },
                error: function(err) {
                    $.each(err.responseJSON.errors, function(i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="error-message" style="color: red;">' +
                                error[0] + '</span>'));
                        })
                }
            });
        });
    </script>
</body>

</html>