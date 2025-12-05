<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIPTA - @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background-color: #343a40;
            transition: margin .25s ease-out;
            margin-left: 0;

            position: fixed;
            top: 0;
            left: 0;
            min-height: 100vh;
            width: 250px;
            /* z-index: 1030; */
        }
        #page-content-wrapper {
            width: 100%;
            margin-left: 250px;
            transition: margin .25s ease-out;
        }
        .sidebar-menu .list-group-item {
            color: #adb5bd;
            background-color: #343a40;
            border: none;
            border-radius: 0;
            padding-top: 1rem;
            padding-bottom: 1rem;
            padding-left: 1.5rem;
        }
        .sidebar-menu .list-group-item:hover, .sidebar-menu .list-group-item.active {
            background-color: #495057;
            color: #fff;
        }
        .sidebar-menu .small {
            color: #6c757d;
            font-weight: bold;
            padding-left: 1.5rem;
        }
        .sidebar-menu .list-group-item.active {
            background-color: #64717d;
            color: #fff;
        }
        body {
            overflow-x: hidden;
        }
        .navbar.fixed-top {
            left: 250px;
            width: calc(100% - 250px);
        }
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -250px;
        }

        #wrapper.toggled #page-content-wrapper {
            margin-left: 0; /* Konten membentang penuh */
        }

        #page-content-wrapper {
            width: 100%;
            margin-left: 250px;
        }

        /* PENGATURAN RESPONSIVE UTAMA */
        @media (max-width: 992px) { /* Untuk layar Tablet dan Mobile (di bawah lg) */
            
            /* Sidebar disembunyikan secara default */
            #sidebar-wrapper {
                margin-left: -250px; 
                /* Tambahkan z-index tinggi agar sidebar muncul di atas konten */
                z-index: 1080; 
            }
            
            /* Konten utama membentang penuh di mobile */
            #page-content-wrapper {
                margin-left: 0; 
                /* Width 100% sudah benar */
            }
            
            /* Saat tombol toggle diklik, sidebar muncul */
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #page-content-wrapper {
                margin-left: 0; /* Tetap 0 */
            }

            /* Navbar fixed harus membentang penuh di mobile */
            .navbar.fixed-top {
                left: 0;
                width: 100%;
        }
    }
    </style>
    @yield('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        <div class="border-end border-secondary" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom text-white bg-dark d-flex align-items-center" style="padding: 1.20rem 1.5rem;">
                <img src="{{ asset('template/img/logosipta.png') }}" alt="Logo SIPTA" class="me-3" style="height: 60px; width: auto;">
                <b style="font-size: 1.5rem; line-height: 1;">SIPTA</b>
            </div>   
            <div class="list-group list-group-flush sidebar-menu">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ Request::is('dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>

                @if (Auth::check() && Auth::user()->userType === 'admin')
                {{-- <div class="text-secondary small mt-3 px-3">MASTER DATA</div> --}}
                    <a href="{{ route('barang.index') }}" class="list-group-item list-group-item-action {{ Request::is('barang*') ? 'active' : '' }}">
                        <i class="fas fa-boxes me-2"></i> Pengelolaan Barang
                    </a>
                    <a href="{{ route('kategori.index') }}" class="list-group-item list-group-item-action {{ Request::is('kategori*') ? 'active' : '' }}">
                        <i class="fas fa-tags me-2"></i> Kategori
                    </a>
                    <a href="{{ route('sales.index') }}" class="list-group-item list-group-item-action {{ Request::is('sales*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Sales
                    </a>
                @endif

                <!-- {{-- <div class="text-secondary small mt-3 px-3">TRANSAKSI & RIWAYAT</div>
                <a href="{{ route('transaksi.create') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-cash-register me-2"></i> Point of Sale (POS)
                </a>
                <a href="{{ route('transaksi.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-receipt me-2"></i> Riwayat Transaksi
                </a> -->
                <!-- <a href="{{ route('riwayat-sales.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-line me-2"></i> Riwayat Sales
                </a> --}} -->

                @if (Auth::check() && Auth::user()->userType === 'owner')
                    <a href="{{ route('laporan_barang.laporan_stok') }}" class="list-group-item list-group-item-action {{ Request::is('laporan-stok-barang*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line me-2"></i> Laporan Barang Terlaris
                    </a>
                    <a href="/laporan-penjualan" class="list-group-item list-group-item-action {{ Request::is('laporan-penjualan*') ? 'active' : '' }}">
                        <i class="fas fa-receipt me-2"></i> Laporan Penjualan
                    </a>
                    <a href="{{ route('sales.index') }}" class="list-group-item list-group-item-action {{ Request::is('sales*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Sales
                    </a>
                    <a href="{{ route('riwayat-sales.index') }}" class="list-group-item list-group-item-action {{ Request::is('riwayat-sales*') || Request::is('detail-riwayat-sales*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-plus me-2"></i> Riwayat Sales
                    </a>
                    <a href="{{ route('user.index') }}" class="list-group-item list-group-item-action {{ Request::is('user*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Kelola User
                    </a>
                @endif

                @if (Auth::check() && Auth::user()->userType === 'kasir')
                    <a href="{{ route('kasir.index') }}" class="list-group-item list-group-item-action {{ Request::is('kasir*') ? 'active' : '' }}">
                        <i class="fas fa-cash-register me-2"></i> Point of Sale (POS)
                    </a>
                @endif
            </div>
        </div>
        <div id="page-content-wrapper" class="pt-5">
            @include('layouts.navbar')
            <div class="container-fluid p-4">
                @include('layouts.flash-message')
                @yield('content')
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            if ($.fn.DataTable) {
                 $('#tabel-produk').DataTable();
            }
            $('#menu-toggle').click(function(e) {
                e.preventDefault();
                $('#wrapper').toggleClass('toggled');

            $('#menu-toggle').click(function(e) {
            e.preventDefault();
            $('#wrapper').toggleClass('toggled');
            });

            // Handler untuk tombol TOGGLE di mobile (icon hamburger)
            $('#menu-toggle-mobile').click(function(e) {
                e.preventDefault();
                $('#wrapper').toggleClass('toggled');
            });
            
            // OPSIONAL: Tambahkan logika untuk menutup sidebar jika area konten diklik di mobile
            $('#page-content-wrapper').on('click', function() {
                if ($(window).width() <= 992 && !$('#wrapper').hasClass('toggled')) {
                    $('#wrapper').addClass('toggled');
                }
            });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
