<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AIGuardians') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('extra_styles')
    @yield('extra_scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div id="app" style="">
        <div class="container-fluid">
            <div class="row">
                <div class="left">
                    @include('partials.navigation')
                </div>
                <div class="right">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
