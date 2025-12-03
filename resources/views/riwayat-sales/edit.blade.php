@extends('layouts.master') 

@section('title', 'Edit Riwayat Kunjungan Sales') 

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Edit Riwayat Kunjungan</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning text-success">
                        Update Status Kunjungan: {{ $riwayat->sales->nama_sales }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('riwayat-sales.update', $riwayat->id) }}" method="POST">
                        @csrf
                        @method('PUT') 
                        <input type="hidden" name="sales_id" value="{{ $riwayat->sales_id }}">
                        <div class="mb-3">
                            <label for="sales_name_display" class="form-label">Nama Sales</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="sales_name_display" 
                                value="{{ $riwayat->sales->nama_sales }}"
                                disabled>
                            <small class="form-text text-muted">Nama sales tidak dapat diubah di halaman ini.</small>
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
                                        {{ (old('status', $riwayat->status) == $status) ? 'selected' : '' }}>
                                        {{ ucwords($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal dan Waktu Kunjungan (Update)</label>
                            @php
                                $tanggalKunjunganValue = $riwayat->tanggal_kunjungan 
                                    ? \Carbon\Carbon::parse($riwayat->tanggal_kunjungan)->format('Y-m-d\TH:i') 
                                    : '';
                            @endphp
                            
                            <input 
                                type="datetime-local" 
                                class="form-control @error('tanggal_kunjungan') is-invalid @enderror" 
                                id="tanggal_kunjungan" 
                                name="tanggal_kunjungan" 
                                value="{{ old('tanggal_kunjungan', $tanggalKunjunganValue) }}">
                                
                            <small class="form-text text-muted">Isi tanggal dan waktu jika sales sudah datang atau sedang dalam proses. Biarkan kosong jika status 'Belum Datang'.</small>
                            
                            @error('tanggal_kunjungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top mt-4">
                            <a href="{{ route('riwayat-sales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                            
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
    
@endsection