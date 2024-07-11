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
                <form method="POST" id="otpForm">
                      @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">



                    <div class="form-group">
                        <x-text-input id="code" class="form-control form-control-lg" type="text" name="code" :value="old('code')" autofocus autocomplete="username" placeholder="OTP" />
                      
                    </div>
                   

                    <button type="submit" class="btn btn-primary btn-lg btn-block signIn">Verify</button>
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
        $('#otpForm').submit(function(e) {
          
                e.preventDefault();
                $('.text-danger').text("");
                var $submitButton = $(this).find('button[type="submit"]');
                var originalText = $submitButton.html();
                $submitButton.text('Wait...');
                $submitButton.prop('disabled', true);

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ url('verify/otp') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success response
                      console.log(response.message);

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
                            console.log(i);
                         //   $(".error-message").text(error);
                         $('.'+i+'Msg').text(error);
                        })

                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    },
                
            });
            
        });
    </script>
</body>

</html>