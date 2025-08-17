<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <meta name="description"
            content="Sneat is the best bootstrap 5 dashboard for responsive web apps. Streamline your app development process with ease." />

        <meta name="keywords"
            content="Sneat bootstrap dashboard, sneat bootstrap 5 dashboard, themeselection, html dashboard, web dashboard, frontend dashboard, responsive bootstrap theme" />
        <meta property="og:title" content="Sneat Bootstrap 5 Dashboard PRO by ThemeSelection" />
        <meta property="og:type" content="product" />
        <meta property="og:url" content="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/" />
        <meta property="og:image"
            content="https://themeselection.com/wp-content/uploads/edd/2024/08/sneat-dashboard-pro-bootstrap-smm-image.png" />
        <meta property="og:description"
            content="Sneat is the best bootstrap 5 dashboard for responsive web apps. Streamline your app development process with ease." />
        <meta property="og:site_name" content="ThemeSelection" />
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assetlogin/images/icons/favicon.ico') }}" />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assetlogin/vendor/bootstrap/css/bootstrap.min.css') }}">

        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css"
            href="{{ asset('assetlogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">

        <!-- Animate -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assetlogin/vendor/animate/animate.css') }}">

        <!-- Hamburgers -->
        <link rel="stylesheet" type="text/css"
            href="{{ asset('assetlogin/vendor/css-hamburgers/hamburgers.min.css') }}">

        <!-- Select2 -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assetlogin/vendor/select2/select2.min.css') }}">

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assetlogin/css/util.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assetlogin/css/main.css') }}">



        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>

    <body>
        @yield('content')

        <!-- Vendor JS -->
        <script src="{{ asset('assetlogin/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('assetlogin/vendor/bootstrap/js/popper.js') }}"></script>
        <script src="{{ asset('assetlogin/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assetlogin/vendor/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assetlogin/vendor/tilt/tilt.jquery.min.js') }}"></script>

        <script>
            $('.js-tilt').tilt({
                scale: 1.1
            });
        </script>




    </body>

</html>
