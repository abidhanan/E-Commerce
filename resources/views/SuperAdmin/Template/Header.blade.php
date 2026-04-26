<nav class="navbar navbar-expand-lg px-3">
    <button class="btn btn-outline-light btn-sm" id="toggleBtn">☰</button>

    <div class="ms-auto dropdown">
        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">

            <i class="bi bi-person-circle me-2"></i>
            {{ auth()->user()->name ?? 'Admin' }}
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li>
                <a class="dropdown-item" href="#">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
