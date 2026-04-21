@extends('layouts.master') 

@section('title', 'Edit Data Barang: ' . $barang->nama) 

@section('content')

    <div class="row">
        <div class="col-md-10 offset-md-1">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Form Edit Barang</h1>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        Edit Barang: {{ $barang->nama }} (Kode: {{ $barang->kode_barang }})
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_barang" class="form-label">Kode Barang</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('kode_barang') is-invalid @enderror" 
                                        id="kode_barang" 
                                        name="kode_barang" 
                                        value="{{ old('kode_barang', $barang->kode_barang) }}"
                                        placeholder="cth: SKM001"
                                        readonly>
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
                                        value="{{ old('nama', $barang->nama) }}"
                                        placeholder="Masukkan nama produk"
                                        required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('stok') is-invalid @enderror" 
                                        id="stok" 
                                        name="stok" 
                                        value="{{ old('stok', $barang->stok) }}"
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
                                        value="{{ old('harga_beli', $barang->harga_beli) }}"
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
                                        value="{{ old('harga_jual', $barang->harga_jual) }}"
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
                                        @foreach ($kategori as $k)
                                            <option 
                                                value="{{ $k->id }}"
                                                {{ (old('id_kategori', $barang->id_kategori) == $k->id) ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="id_sales" class="form-label">Sales</label>
                                    <select class="form-select @error('id_sales') is-invalid @enderror" id="id_sales" name="id_sales" required>
                                        <option value="">Pilih Sales</option>
                                        @foreach ($sales as $s)
                                            <option 
                                                value="{{ $s->id }}"
                                                {{ (old('id_sales', $barang->id_sales) == $s->id) ? 'selected' : '' }}>
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