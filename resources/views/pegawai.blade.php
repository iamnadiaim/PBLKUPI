@extends('layouts.app')

@section('title', 'Tambah Pegawai')
<i class="fa-duotone fa-users-gear"></i>
@section('contents')

<body class="bg-light">
    <div class="container mt-2">
        <form action="{{ route('tambah-pegawai.store') }}" class="signup-form bg-white p-4 rounded" method="POST">
            {{-- <a href="{{ route('pegawais.laporan') }}" class="btn btn-warning" style="margin-top: 10px">Lihat Laporan </a> --}}
            @csrf

            @if (session()->has('successAddSekolah'))
            <div class="toast-wrapper">
                <div class="toast-succes">
                    <div class="toast-succes-container">
                        <div class="icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="content">
                            <h1>Succes</h1>
                            <p>Akun Berhasil Dibuat</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" class="form-control form-control-sm" placeholder="Nama" required value="{{ old('nama') }}">
                @error('nama')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" class="form-control form-control-sm" placeholder="Email" required value="{{ old('email') }}">
                @error('email')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_telepon">No Telepon:</label>
                <input type="text" id="no_telepon" name="no_telepon" class="form-control form-control-sm" placeholder="No Telepon" required>
                @error('no_telepon')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
                @error('password')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-sm">Create an Account</button>
            </div>
            


        </form>
    </div>

    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('vanilla-toast.min.js') }}"></script>
</body>

@endsection
