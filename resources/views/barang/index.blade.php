@extends('layouts.master') 

@section('title', 'Pengelolaan Data Barang') 

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Barang</h1>
        <a href="{{ route('barang.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Data Produk Toko</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-barang" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Kategori</th>
                            <th>Sales</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($barang as $barang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $barang->kode_barang }}</td>
                                    <td>{{ $barang->nama}}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $barang->stok < 10 ? 'text-bg-danger' 
                                                : ($barang->stok <= 20 ? 'text-bg-warning' 
                                                : 'text-bg-success') 
                                            }}">
                                            {{ $barang->stok }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                    <td>{{ $barang->kategori->nama_kategori ?? 'N/A' }}</td>
                                    <td>{{ $barang->sales->nama_sales ?? 'N/A' }}</td>
                                    
                                    <td>
                                        <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus barang {{ $barang->nama_barang }}?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#tabel-barang').DataTable();
        });
    </script>
@endsection