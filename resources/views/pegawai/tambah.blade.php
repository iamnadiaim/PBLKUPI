@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('contents')
<div class="container mt-5">

    @if (session()->has('successAddSekolah'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Akun Berhasil Dibuat.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mx-auto w-50"> <!-- Tambahkan mx-auto dan w-50 -->
        <div class="card-body">
            <form action="{{ route('tambah-pegawai.store') }}" method="POST" class="form-signup">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    @error('nama')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="alamat" class="form-label">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" required>
                    @error('alamat')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="no_telepon" class="form-label">No Telepon:</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-control" required>
                    @error('no_telepon')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <input type="submit" class="btn btn-primary" value="Tambah">
                    <a href="{{ route('daftarPegawai') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="script.js"></script>
<script src="{{ asset('vanilla-toast.min.js') }}"></script>
@endsection