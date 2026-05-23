@extends('layouts.master') 

@section('title', 'Tambah Jadwal Kunjungan Sales') 

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Jadwal Kunjungan Baru</h1>
            </div>

            {{-- TAMBAHAN: Alert Peringatan untuk Mengingatkan Restok Produk --}}
            @if(session('info_restok'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm border-left-info d-flex align-items-center justify-content-between" role="alert" style="border-left: 0.25rem solid #36b9cc !important;">
                    <div>
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info_restok') }}
                    </div>
                    {{-- Menggunakan class bawaan Bootstrap 5 (btn-close) atau modifikasi tombol close --}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="background: none; border: none; font-size: 1.25rem; line-height: 1; color: #0c5460; cursor: pointer;">&times;</button>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Input Jadwal Kunjungan Sales</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('riwayat-sales.store') }}" method="POST">
                        @csrf

                        {{-- 2. LOGIKA DISABLE DROPDOWN JIKA MASUK DARI DASHBOARD --}}
                        {{-- Jika ada request('sales_id') dari URL, buat input hidden agar datanya tetap terkirim saat submit --}}
                        @if(request('sales_id'))
                            <input type="hidden" name="sales_id" value="{{ request('sales_id') }}">
                        @endif
                        <div class="mb-3">
                            <label for="sales_id" class="form-label">Nama Sales</label>
                            <select 
                                class="form-select @error('sales_id') is-invalid @enderror" 
                                id="sales_id" 
                                name="sales_id" 
                                required
                                {{-- Jika masuk membawa parameter sales_id dari dashboard, otomatis disable --}}
                                {{ request('sales_id') ? 'disabled' : '' }}>
                                
                                <option value="">Pilih Sales</option>
                                @foreach ($sales as $s)
                                    <option 
                                        value="{{ $s->id }}"
                                        {{-- MODIFIKASI: Cek old input ATAU cek sales_id dari lemparan URL dashboard --}}
                                        {{ (old('sales_id') == $s->id || request('sales_id') == $s->id) ? 'selected' : '' }}>
                                        {{ $s->nama_sales }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kedatangan</label>
                            <select 
                                class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                                
                                @php
                                    $statuses = ['belum datang', 'sudah datang'];
                                @endphp
                                
                                @foreach ($statuses as $status)
                                    <option 
                                        value="{{ $status }}"
                                        {{ (old('status') == $status) ? 'selected' : '' }}>
                                        {{ ucwords($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal dan Waktu Kunjungan (Opsional)</label>
                            <input 
                                type="datetime-local" 
                                class="form-control @error('tanggal_kunjungan') is-invalid @enderror" 
                                id="tanggal_kunjungan" 
                                name="tanggal_kunjungan" 
                                value="{{ old('tanggal_kunjungan') }}">
                                
                            <small class="form-text text-muted">Biarkan kosong jika status masih 'Belum Datang'.</small>
                            
                            @error('tanggal_kunjungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('riwayat-sales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
    
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        const tanggalInput = document.getElementById('tanggal_kunjungan');

        function sesuaikanInputTanggal() {
            // Jika status yang dipilih adalah 'belum datang'
            if (statusSelect.value === 'belum datang') {
                tanggalInput.disabled = true;
                tanggalInput.value = ''; // Mengosongkan nilai jika owner sempat mengisi lalu mengubah status
                tanggalInput.required = false;
            } else {
                // Jika status yang dipilih adalah 'sudah datang'
                tanggalInput.disabled = false;
                tanggalInput.required = true; // Opsional: mewajibkan isi tanggal jika sudah datang
            }
        }

        // Jalankan fungsi saat pertama kali halaman dimuat (untuk mengecek data 'old' atau default)
        sesuaikanInputTanggal();

        // Jalankan fungsi setiap kali owner mengubah pilihan dropdown status
        statusSelect.addEventListener('change', sesuaikanInputTanggal);
    });
</script>