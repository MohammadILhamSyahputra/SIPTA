<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        
        {{-- Tombol Toggle Sidebar (jika Sidebar perlu disembunyikan/ditampilkan) --}}
        <button class="btn btn-primary" id="menu-toggle">
            <i class="fas fa-bars"></i> Menu
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            {{-- Bagian Kanan: Nama Pengguna dan Dropdown --}}
            {{--<ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- Ganti dengan nama pengguna yang sedang login --}}
                        {{--**{{ Auth::user()->name ?? 'Nama Pengguna' }}**--}}
                    {{-- </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li> --}}
                            {{-- Form untuk tombol Logout --}}
                            {{-- <a class="dropdown-item" href="/logout" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li> --}}
            {{-- </ul>--}} 
        </div>
    </div>
</nav>