<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
<body class="pc-auth">
    <main class="pc-auth-shell">

        <section class="pc-auth-brand">

            <img src="{{ asset('imagen/logo.png') }}"
                 alt="Pie Clinic - Consultorio Podológico"
                 class="pc-auth-logo">

            <p>Sistema integral de gestión podológica</p>

        </section>

        <section class="pc-auth-form">
            {{ $slot }}
        </section>

    </main>
</body>

    </html>