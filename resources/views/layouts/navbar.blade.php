<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top">
    <div class="container-fluid">
        
        <a class="nav-link text-dark me-3" href="#" id="menu-toggle" title="Klik untuk menyembunyikan/menampilkan Sidebar">
            @php
                $userRole = Auth::user()->userType ?? 'Admin';
            @endphp
            <span class="fw-bold">{{ strtoupper($userRole) }}</span>
        </a>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1 fa-xl"></i>
                        <b>{{ Auth::user()->name ?? 'Putri Ardiyana' }}</b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"
                    style="background-color: #dc3545; color: white; border-radius: 4px; margin: 5px;">
                        {{-- <li><a class="dropdown-item" href="/profile">Profile</a></li> --}}
                        {{-- <li><hr class="dropdown-divider"></li> --}}
                        <li>
                            <a class="dropdown-item" href="/logout" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <b>LOGOUT</b>
                            </a>
                            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>