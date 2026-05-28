<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- ✅ CSRF Token pour les requêtes AJAX -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name')}} - @yield('title') </title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('assets/app.css') }}">

        <!-- Tailwind CSS compilé avec Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles / Scripts -->
    </head>
    <body>

        @include('navbar.navbar')

        @yield('content')

        <!-- jQuery -->
        <script src="{{ asset('assets/lib/bootstrap/jquery/jquery.js')}}"></script>
        
        <!-- Bootstrap JavaScript Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Script utilisateur -->
        <script src="{{ asset('assets/main/user/user.js')}}"></script>
        
        <!-- Alpine.js CDN (pour le chatbot AI) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Script simple pour dropdowns -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple initialisation Bootstrap dropdowns
            if (typeof bootstrap !== 'undefined') {
                const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
                dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            }
        });
        </script>        @include('footer.footer')

        @yield('scripts')


    </body>
</html>
