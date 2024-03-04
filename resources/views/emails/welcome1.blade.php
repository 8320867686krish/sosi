<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        /* styles.css */
        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .list-group-item {
            border: none;
            padding: 5px;
        }

        .loginBtn{
            color: #fff;
            background-color: #5969ff;
            border-color: #5969ff;
            padding: 15px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Welcome to our platform!</h2>
                        <p class="card-text">Here are your account details:</p>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Email:</strong> {{ $user['email'] }}</li>
                            <li class="list-group-item"><strong>Password:</strong> {{ $user['password'] }}</li>
                        </ul>
                        <div class="btn-container">
                            <a href="{{ route('login') }}" class="btn btn-primary loginBtn">Login</a>
                        </div>
                        @if ($user['isAppAccess'])
                            <div class="btn-container">
                                <a href="{{ route('login') }}" class="btn btn-primary loginBtn">Download App</a>
                            </div>
                        @endif
                        <p class="card-text mt-3">If you have any questions, feel free to contact us.</p>
                        <p class="card-text">Thanks,<br>{{ config('app.name') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
