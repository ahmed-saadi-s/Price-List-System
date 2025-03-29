<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.dashboard') }}</title>
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('styles')
</head>

<body>

    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="logo">
                <h1>{{ __('messages.project_name') }}</h1>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="{{ route('dashboard.home') }}">{{ __('messages.home') }}</a></li>
                    <li>
                        <form action="{{ route('dashboard.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-link logout-button">{{ __('messages.logout') }}</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="main-content">
            <aside class="sidebar">
                <ul>
                    <li><a href="{{ route('dashboard.products.index') }}">{{ __('messages.products') }}</a></li>
                    <li><a href="{{ route('dashboard.countries.index') }}">{{ __('messages.countries') }}</a></li>
                    <li><a href="{{ route('dashboard.currencies.index') }}">{{ __('messages.currencies') }}</a></li>
                </ul>
            </aside>

            <section class="content">
                @yield('content')
            </section>
        </div>

        <footer class="dashboard-footer">
            <p>{{ __('messages.footer_text') }}</p>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
