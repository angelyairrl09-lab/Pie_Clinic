
<aside class="pc-sidebar" id="sidebar">

    <div class="pc-brand">

        <img src="{{ asset('imagen/logo.png') }}"
                 alt="Pie Clinic - Consultorio Podológico"
                 class="pc-auth-logo">

        <small>Gestión podológica</small>

    </div>

    <nav class="pc-menu nav flex-column">

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            Inicio
        </a>

        <small class="text-white-50 px-3 mt-3 mb-2">
            GESTIÓN CLÍNICA
        </small>

        <a href="{{ route('pacientes.index') }}"
   class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">

    <i class="bi bi-people"></i>
    Pacientes

</a>

        <a href="{{ route('citas.index') }}"
   class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">

    <i class="bi bi-calendar3"></i>
    Agenda de citas

</a>

        <a href="{{ route('expedientes.index') }}"
   class="nav-link {{ request()->routeIs('expedientes.*') ? 'active' : '' }}">
    <i class="bi bi-folder2-open"></i>
    Expedientes clínicos
</a>

<a href="{{ route('diagnosticos.index') }}"
   class="nav-link {{ request()->routeIs('diagnosticos.*') ? 'active' : '' }}">

    <i class="bi bi-clipboard2-pulse"></i>
    Diagnóstico e imágenes

</a>

        <a href="{{ route('consultas.index') }}"
   class="nav-link {{ request()->routeIs('consultas.*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-pulse"></i>
    Consultas podológicas
</a>

        <a href="{{ route('tratamientos.index') }}"
   class="nav-link {{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
    <i class="bi bi-bandaid"></i>
    Tratamientos
</a>

        <a href="#" class="nav-link">
            <i class="bi bi-activity"></i>
            Seguimientos
        </a>

        <small class="text-white-50 px-3 mt-3 mb-2">
            ADMINISTRACIÓN
        </small>

        <a href="#" class="nav-link">
            <i class="bi bi-box-seam"></i>
            Inventario
        </a>

        <a href="#" class="nav-link">
            <i class="bi bi-wallet2"></i>
            Finanzas
        </a>

        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart"></i>
            Reportes
        </a>

        <a href="#" class="nav-link">
            <i class="bi bi-person-gear"></i>
            Usuarios
        </a>

        <a href="#" class="nav-link">
            <i class="bi bi-gear"></i>
            Configuración
        </a>

    </nav>

</aside>
