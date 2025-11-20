@extends('layouts.master') 

@section('title', 'Tambah Data Barang Baru') 

@section('content')

    <div class="row">
        <div class="col-md-10 offset-md-1">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Tambah Barang</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Detail Produk Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label for="kode_barang" class="form-label">Kode Barang (Opsional)</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('kode_barang') is-invalid @enderror" 
                                        id="kode_barang" 
                                        name="kode_barang" 
                                        value="{{ old('kode_barang') }}"
                                        placeholder="cth: SKM001">
                                    @error('kode_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Barang</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('nama') is-invalid @enderror" 
                                        id="nama" 
                                        name="nama" 
                                        value="{{ old('nama') }}"
                                        placeholder="Masukkan nama barang"
                                        required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok Awal</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('stok') is-invalid @enderror" 
                                        id="stok" 
                                        name="stok" 
                                        value="{{ old('stok', 0) }}"
                                        min="0"
                                        required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>
                            
                            <div class="col-md-6">
                                
                                <div class="mb-3">
                                    <label for="harga_beli" class="form-label">Harga Beli (Modal)</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('harga_beli') is-invalid @enderror" 
                                        id="harga_beli" 
                                        name="harga_beli" 
                                        value="{{ old('harga_beli', 0) }}"
                                        min="0"
                                        required>
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('harga_jual') is-invalid @enderror" 
                                        id="harga_jual" 
                                        name="harga_jual" 
                                        value="{{ old('harga_jual', 0) }}"
                                        min="0"
                                        required>
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label">Kategori</label>
                                    <select class="form-select @error('id_kategori') is-invalid @enderror" id="id_kategori" name="id_kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategori as $kategori)
                                            <option 
                                                value="{{ $kategori->id }}"
                                                {{ (old('id_kategori') == $kategori->id) ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="id_sales" class="form-label">Sales / Pencatat</label>
                                    <select class="form-select @error('id_sales') is-invalid @enderror" id="id_sales" name="id_sales" required>
                                        <option value="">Pilih Sales</option>
                                        @foreach ($sales as $s)
                                            <option 
                                                value="{{ $s->id }}"
                                                {{ (old('id_sales') == $s->id) ? 'selected' : '' }}>
                                                {{ $s->nama_sales }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_sales')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top mt-4">
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection