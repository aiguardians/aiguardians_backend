<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('extra_styles')
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app" style="">
        <div class="container-fluid">
            <div class="row" style="min-height: 100vh;">
                <div class="d-flex">
                    <div class="d-inline-flex">
                        @include('partials.navigation')
                    </div>
                    <div class="d-inline-flex m-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        @yield('extra_scripts')
    </div>
</body>
</html>
