@extends('layouts.master') 

@section('title', 'Pengelolaan Data Sales') 

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Sales</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Sales Baru
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Data Sales</h6>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table id="tabel-sales" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sales</th>
                            <th>No telp</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($sales as $sales)
                                <tr>
                                    {{-- <td>{{ $kategori->id }}</td> --}}
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sales->nama_sales }}</td>
                                    <td>{{ $sales->no_telp }}</td>
                                    <td>{{ $sales->alamat }}</td>
                                    <td>
                                        <a href="{{ route('sales.edit', $sales->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sales->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus sales {{ $sales->nama_sales }}?')" title="Hapus">
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
            $('#tabel-sales').DataTable();
        });
    </script>
@endsection