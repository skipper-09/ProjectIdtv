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
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/components.css') }}"> --}}

    <style>
        .text-right {
            text-align: right;
        }

        .card {
            border-radius: 0;
            border: none;
        }

        .table {
            border-bottom-width: none;
        }

        .table> :not(caption)>*>* {
            padding-top: 0;
            padding-bottom: 0
        }

        .btn {
            border: solid 1px #ccc;
        }

        .left-col {
            max-width: 75px;
        }

        @media print {
            @page {
                size: auto;
                margin: 0;
            }

            @media all and (-webkit-min-device-pixel-ratio: 0) and (min-resolution: .001dpcm) {
                body {
                    size: auto;
                    background: transparent;
                    border: none;
                    padding: 0;
                    margin-top: -0.4cm;
                    margin-left: -0.22cm;
                    margin-right: 1.1cm;
                    max-width: 100%;
                }

                .container-fluid {
                    margin-top: 18px;
                }
            }

            @-moz-document url-prefix() {
                body {
                    size: auto;
                    background: transparent;
                    border: none;
                    padding: 0;
                    margin-top: -0.4cm;
                    margin-left: -0.22cm;
                    margin-right: 0.9cm;
                    max-width: 100%;
                }
            }

            .table> :not(caption)>*>* {
                padding-top: 0;
                padding-bottom: 0
            }

            .container-fluid,
            .footer {
                border: none;
                font-size: 10px;
            }

            .page-break {
                display: block;
                page-break-before: always;
            }

            .no-print,
            .no-print * {
                display: none !important;
            }
        }

        * {
            line-height: 1.5;
            font-family: 'Arial Narrow';
            color: black;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
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
