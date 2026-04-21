@extends('layouts.master') 

@section('title', 'Tambah Jadwal Kunjungan Sales') 

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Jadwal Kunjungan Baru</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Input Jadwal Kunjungan Sales</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('riwayat-sales.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="sales_id" class="form-label">Nama Sales</label>
                            <select 
                                class="form-select @error('sales_id') is-invalid @enderror" 
                                id="sales_id" 
                                name="sales_id" 
                                required>
                                
                                <option value="">Pilih Sales</option>
                                @foreach ($sales as $s)
                                    <option 
                                        value="{{ $s->id }}"
                                        {{ (old('sales_id') == $s->id) ? 'selected' : '' }}>
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
                                    $statuses = ['belum datang', 'proses', 'sudah datang'];
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