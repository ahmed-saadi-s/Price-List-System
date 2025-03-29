<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('messages.welcome') }}</title>
        <link href="{{ asset('css/home.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body>
        <header>
            <h1>{{ __('messages.project_name') }}</h1>
        </header>
        
        <div class="container">
            @yield('content')
        </div>
        
        <footer>
            <p>{{ __('messages.footer_text') }}</p>
        </footer>
    </body>
</html>
