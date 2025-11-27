@extends('layouts.master')

@section('title', 'Tambah Sales Baru')

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Tambah Sales</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Data Sales</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_sales" class="form-label">Nama Sales</label>
                            <input
                                type="text"
                                class="form-control @error('nama_sales') is-invalid @enderror"
                                id="nama_sales"
                                name="nama_sales"
                                value="{{ old('nama_sales') }}"
                                placeholder="Masukkan nama sales"
                                required>

                            @error('nama_sales')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input
                                type="text"
                                class="form-control @error('no_telp') is-invalid @enderror"
                                id="no_telp"
                                name="no_telp"
                                value="{{ old('no_telp') }}"
                                placeholder="Masukkan nomor telepon aktif"
                                required>

                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea
                                class="form-control @error('alamat') is-invalid @enderror"
                                id="alamat"
                                name="alamat"
                                rows="3"
                                placeholder="Masukkan alamat lengkap sales"
                                required>{{ old('alamat') }}</textarea>

                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Sales
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
