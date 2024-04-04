<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code PDF</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .qr-code {
            text-align: center;
            margin-bottom: 20px;
        }
        .qr-code img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4 text-center">QR Code PDF</h1>
        <div class="row">
            @foreach ($checks as $check)
            <div class="col-md-4 mb-4">
                <div class="qr-code">
                    <img src="{{ $check['qr_code_url'] }}" alt="QR Code for Check {{ $check['id'] }}" class="img-fluid">
                    <p>{{ $check['name'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
