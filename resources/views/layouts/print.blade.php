<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title')</title>

    <!-- General CSS Files -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/IDTV.png') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">


    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/components.css') }}"> --}}

    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */
        }
    </style>

    <style>
        .invoice-container {
            margin: 15px auto;
            padding: 40px;
            max-width: 850px !important;
            background-color: #fff;
            border: 1px solid #ccc;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            -o-border-radius: 6px;
            border-radius: 6px;
        }
    </style>
</head>

<body>


    @yield('print')



    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>


    @stack('scripts')


</body>

</html>
