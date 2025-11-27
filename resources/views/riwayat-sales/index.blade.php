@extends('layouts.master') 

@section('title', 'Riwayat Kunjungan Sales') 

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Kunjungan Sales</h1>
        <a href="{{ route('riwayat-sales.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-calendar-plus fa-sm text-white-50"></i> Tambah Jadwal Kunjungan
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Sales</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabel-riwayat" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sales</th>
                            <th>Status Kunjungan</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($riwayat as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->sales->nama_sales ?? 'Sales Dihapus' }}</td>

                                    <td>
                                        @php
                                            $statusClass = [
                                                'belum datang' => 'text-bg-danger',
                                                'proses' => 'text-bg-info',
                                                'sudah datang' => 'text-bg-success',
                                            ][$item->status] ?? 'text-bg-light';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ strtoupper($item->status) }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        @if ($item->tanggal_kunjungan)
                                            {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d F Y, H:i') }}
                                        @else
                                            <span class="text-danger">-- Belum Dicatat --</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        
                                        <a href="{{ route('riwayat-sales.edit', $item->id) }}" class="btn btn-sm btn-warning me-1" title="Edit Status & Tanggal">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('riwayat-sales.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus riwayat ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @if ($item->status === 'sudah datang')
                                            <a href="{{ route('riwayat-sales.show', $item->id) }}" class="btn btn-sm btn-primary me-1" title="Lihat Detail Transaksi">
                                                <i class="fas fa-search"></i> Detail
                                            </a>
                                        @endif
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
            $('#tabel-riwayat').DataTable();
        });
    </script>
@endsection