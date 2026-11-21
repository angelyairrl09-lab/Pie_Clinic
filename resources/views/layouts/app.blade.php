
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inicio') | Pie Clinic</title>

    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>

    @include('partials.sidebar')

    <div class="pc-main">

        @include('partials.navbar')

        <main class="pc-content">
            @yield('content', $slot ?? '')
        </main>

        @include('partials.footer')

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (toggle && sidebar) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
