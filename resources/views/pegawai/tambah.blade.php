@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('contents')

        <div class="wrapper">
            <form action="{{ route('tambah-pegawai.store') }}" class="signup-form" method="POST">
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
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                    @error('nama')
                        <span class="">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" id="email" name="email" class="form-control" required>
                    @error('email')
                        <span class="">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" required>
                    @error('alamat')
                        <span class="">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="noTelepon" class="form-label">No Telepon:</label>
                    <input type="text" id="noTelepon" name="noTelepon" class="form-control" required>
                    @error('noTelepon')
                        <span class="">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="text" id="password" name="password" class="form-control" required>
                    @error('password')
                        <span class="">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-3" style="text-align: left;">
                    <input type="submit" class="btn btn-primary" value="Tambah">
                    <a href="{{ route('daftarPegawai') }}" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="{{asset('vanilla-toast.min.js')}}"></script>
</body>

@endsection
