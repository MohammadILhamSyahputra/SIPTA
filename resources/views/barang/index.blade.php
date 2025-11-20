@extends('layouts.master') 

{{-- Menyesuaikan judul halaman --}}
@section('title', 'Pengelolaan Data Barang') 

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Barang</h1>
        {{-- Tautan ke halaman tambah barang (sesuai route resource: barang.create) --}}
        <a href="{{ route('barang.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
        </a>
    </div>

    {{-- Kartu untuk membungkus tabel --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Produk Toko</h6>
        </div>
        <div class="card-body">
            {{-- Menggunakan class table-responsive agar tabel rapi di tampilan mobile --}}
            <div class="table-responsive">
                
                {{-- ID tabel harus disesuaikan dengan inisialisasi DataTables di master.blade.php (jika berbeda dari 'tabel-produk') --}}
                <table id="tabel-barang" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Kategori</th>
                            <th>Sales/Pencatat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOPING DATA BARANG DARI CONTROLLER AKAN DITEMPATKAN DI SINI --}}
                        
                        {{-- Contoh satu baris data --}}
                        @php
                            // Asumsi $barang adalah objek data dari controller
                            $contohBarang = (object)[
                                'id' => 101,
                                'kode_barang' => 'KPL001',
                                'nama_barang' => 'Kopi Bubuk 250g',
                                'stok' => 150,
                                'harga_beli' => 15000,
                                'harga_jual' => 18500,
                                'nama_kategori' => 'Minuman',
                                'nama_sales' => 'Budi Santoso'
                            ];
                        @endphp
                        
                        <tr>
                            <td>{{ $contohBarang->id }}</td>
                            <td>**{{ $contohBarang->kode_barang }}**</td>
                            <td>{{ $contohBarang->nama_barang }}</td>
                            <td>
                                {{-- Warna stok kritis dapat ditambahkan di sini --}}
                                <span class="badge text-bg-success">{{ $contohBarang->stok }}</span>
                            </td>
                            <td>Rp {{ number_format($contohBarang->harga_beli, 0, ',', '.') }}</td>
                            <td>**Rp {{ number_format($contohBarang->harga_jual, 0, ',', '.') }}**</td>
                            <td>{{ $contohBarang->nama_kategori }}</td>
                            <td>{{ $contohBarang->nama_sales }}</td>
                            <td>
                                {{-- Tombol Aksi --}}
                                <a href="{{ route('barang.edit', $contohBarang->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('barang.destroy', $contohBarang->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        {{-- AKHIR LOOPING DATA BARANG --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    {{-- Inisialisasi DataTables untuk tabel dengan ID 'tabel-barang' --}}
    <script>
        $(document).ready(function () {
            $('#tabel-barang').DataTable();
        });
    </script>
@endsection