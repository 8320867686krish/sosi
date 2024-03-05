<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            border-radius: 8px;
        }

        .card-title {
            font-size: 24px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .card-text {
            margin-bottom: 15px;
            color: #555;
        }

        .credential-details {
            margin-bottom: 20px;
        }

        .credential-details p {
            margin: 5px 0;
        }

        .btn-container {
            text-align: center;
        }

        .btn {
            color: #fff;
            background-color: #017cc0;
            border: none;
            padding: 12px 20px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #015e8e;
        }

        .mt-3 {
            margin-top: 20px;
        }

        .signature {
            margin-top: 30px;
            text-align: center;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="card-img-top" src="{{ url('assets/images/welcome_mail_img.jpg') }}" alt="Welcome Image">
        <h4 class="card-title">Hi
            {{ ucfirst($user['name'] ?: '') . ($user['last_name'] ? ' ' . ucfirst($user['last_name']) : '') }},</h4>
        <p class="card-text">As a token of our appreciation for your loyalty, we're excited to offer you an exclusive
            opportunity.</p>
        <div class="credential-details">
            <h3>Below Your Credential Details:</h3>
            <p><strong>Email:</strong> {{ $user['email'] }}</p>
            <p><strong>Password:</strong> {{ $user['password'] }}</p>
        </div>
        <div class="btn-container">
            <a href="{{ route('login') }}" class="btn">Login</a>
            @if ($user['isAppAccess'])
                <a href="{{ route('login') }}" class="btn">Download App</a>
            @endif
        </div>
        <p class="card-text mt-3">This exclusive offer is our way of saying thank you for being a valued member of our
            community. Whether you've had your eye on that must-have item or you're planning to explore our latest
            arrivals, now is the perfect time to treat yourself.</p>
        <p class="signature">Best regards,<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
