@extends('layouts.master') 

@section('title', 'Tambah Kategori Baru') 

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Tambah Kategori</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Data Kategori</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input 
                                type="text" 
                                class="form-control @error('nama_kategori') is-invalid @enderror" 
                                id="nama_kategori" 
                                name="nama_kategori" 
                                value="{{ old('nama_kategori') }}"
                                placeholder="Masukkan nama kategori"
                                required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Kategori
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
    
@endsection