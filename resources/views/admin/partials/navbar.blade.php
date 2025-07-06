<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-sm btn-outline-dark me-2" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>