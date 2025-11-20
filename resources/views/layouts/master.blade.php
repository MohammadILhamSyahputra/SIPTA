<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Toko - @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body { background-color: #f8f9fa; }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background-color: #343a40;
            transition: margin .25s ease-out;
        }
        #page-content-wrapper {
            width: 100%;
        }
        .sidebar-menu .list-group-item {
            color: #adb5bd; 
            background-color: #343a40;
            border: none;
            border-radius: 0;
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
    </style>
    @yield('styles')
</head>
<body>

    <div class="d-flex" id="wrapper">
        
        <!-- Sidebar -->
        <div class="border-end border-secondary" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom p-3 text-white bg-dark">
                <img src="{{ asset('template/img/logoSipta.jpg') }}" alt="Logo SIPTA" class="me-2" style="height: 30px; width: auto;"><b>SIPTA</b>
            </div>
            
            <!-- Menu Sidebar (Menggunakan Route Anda) -->
            <div class="list-group list-group-flush sidebar-menu">
                
                {{-- Ganti '/' dengan route dashboard Anda jika ada route khusus --}}
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                
                <div class="text-secondary small mt-3 px-3">MASTER DATA</div>
                <a href="{{ route('barang.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-boxes me-2"></i> Pengelolaan Barang
                </a>
                <a href="{{ route('kategori.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-tags me-2"></i> Kategori
                </a>
                <a href="{{ route('sales.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-users me-2"></i> Sales / User
                </a>
                
                <div class="text-secondary small mt-3 px-3">TRANSAKSI & RIWAYAT</div>
                {{-- Transaksi.create digunakan untuk halaman POS (input transaksi baru) --}}
                <a href="{{ route('transaksi.create') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-cash-register me-2"></i> Point of Sale (POS)
                </a>
                {{-- Transaksi.index digunakan untuk riwayat/daftar transaksi --}}
                <a href="{{ route('transaksi.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-receipt me-2"></i> Riwayat Transaksi
                </a>
                <a href="{{ route('riwayat-sales.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-line me-2"></i> Riwayat Sales
                </a>
                
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper">

            <!-- Navbar (Top Header) - Dipanggil dari file terpisah -->
            @include('layouts.navbar')

            <!-- Main Content Area -->
            <div class="container-fluid p-4">
                {{-- Flash Message dipanggil di sini --}}
                @include('layouts.flash-message') 
                
                {{-- KONTEN UTAMA HALAMAN --}}
                @yield('content')
            </div>
            <!-- /Main Content -->

        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Inisiasi DataTables default untuk tabel dengan ID 'tabel-produk'
            if ($.fn.DataTable) {
                 $('#tabel-produk').DataTable();
            }
           
            // Script untuk toggle sidebar (opsional)
            $('#menu-toggle').click(function(e) {
                e.preventDefault();
                $('#wrapper').toggleClass('toggled');
            });
        });
    </script>
    
    @yield('scripts')

</body>
</html>