<!doctype html>
<html lang="en">


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ ucfirst(Request::segment(1)) }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/extrastyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon"/>
    @yield('css')
    @show
</head>
