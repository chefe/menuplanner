<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Menuplanner') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-brand-lightest font-sans font-normal">
    <div class="flex flex-col">

        <div class="min-h-screen flex items-center justify-center">
            <div class="flex flex-col justify-around h-full bg-white shadow p-8">
                <div>
                    <h1 class="text-grey-darker text-center font-hairline tracking-wide text-7xl mb-8 p-8">
                        {{ config('app.name', 'Menuplanner') }}
                    </h1>
                    
                    <div class="flex text-center">
                        @auth
                            <a href="{{ url('/') }}" class="flex-1 no-underline text-sm font-normal text-brand-dark uppercase">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 no-underline hover:underline text-sm font-normal text-brand-dark uppercase">Login</a>
                            <a href="{{ route('register') }}" class="flex-1 no-underline hover:underline text-sm font-normal text-brand-dark uppercase">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>