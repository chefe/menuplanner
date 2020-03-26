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
<body class="bg-green-100 h-screen">
    <div id="app">
        <nav class="bg-white h-12 shadow mb-8">
            <div class="container mx-auto h-full">
                <div class="flex items-center justify-center h-12">
                    <div class="mr-6 pl-4">
                        <a href="{{ url('/') }}" class="text-gray-700 text-xl">
                            <svg class="inline w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M18 11v7a2 2 0 0 1-4 0v-5h-2V3a3 3 0 0 1 3-3h3v11zM4 10a2 2 0 0 1-2-2V1a1 1 0 0 1 2 0v4h1V1a1 1 0 0 1 2 0v4h1V1a1 1 0 0 1 2 0v7a2 2 0 0 1-2 2v8a2 2 0 0 1-4 0v-8z"/></svg>
                            {{ config('app.name', 'Menuplanner') }}
                        </a>
                    </div>
                    <div class="flex-1 text-right pr-4">
                        @if (app()->getLocale() == 'en')
                            <a class="hover:underline text-gray-700 pr-3 text-sm" href="{{ url('/locale/de') }}">[DE]</a>
                        @else
                            <a class="hover:underline text-gray-700 pr-3 text-sm" href="{{ url('/locale/en') }}">[EN]</a>
                        @endif

                        @guest
                            <a class="hover:underline text-gray-700 pr-3 text-sm" href="{{ url('/login') }}">@lang('app.login')</a>
                            <a class="hover:underline text-gray-700 text-sm" href="{{ url('/register') }}">@lang('app.register')</a>
                        @else
                            <a href="{{ route('logout') }}"
                                class="hover:underline text-gray-700 text-sm"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">@lang('app.logout')</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
