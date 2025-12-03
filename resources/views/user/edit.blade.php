@extends('layouts.master')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Peran Pengguna</h1>

@include('layouts.flash-message')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Edit Peran: {{ $user->name }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Pengguna</label>
                <input type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            <div class="mb-3">
                <label for="userType" class="form-label font-weight-bold">Peran Baru</label>
                <select name="userType" id="userType" class="form-control @error('userType') is-invalid @enderror" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ old('userType', $user->userType) === $role ? 'selected' : '' }}>
                            {{ strtoupper($role) }}
                        </option>
                    @endforeach
                </select>
                @error('userType')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Perubahan Peran</button>
        </form>
    </div>
</div>
@endsection