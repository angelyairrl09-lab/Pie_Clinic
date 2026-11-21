
<header class="pc-navbar">

    <div class="d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center gap-3">

            <button class="btn btn-outline-secondary d-lg-none"
                    id="sidebarToggle"
                    aria-label="Abrir menú">
                <i class="bi bi-list"></i>
            </button>

            <span class="fw-semibold">
                @yield('title', 'Inicio')
            </span>

        </div>

        @auth

            <div class="dropdown">

                <button class="btn btn-light dropdown-toggle"
                        data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle me-2"></i>

                    {{ Auth::user()->name }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>
                            Mi perfil
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Cerrar sesión
                            </button>

                        </form>
                    </li>

                </ul>

            </div>

        @endauth

    </div>

</header>
