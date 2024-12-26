@extends('layouts.app')

@section('title', 'Edit Profile')

@section('contents')

<div class="main">
    <div class="tanggal">
        <p id="tanggal"></p>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-8eb">
            <form action="{{ route('editProfile') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" id="nama" value="{{ old('nama', $nama) }}" placeholder="Nama" name="nama" class="form-control" required>
                    @error('nama')
                        <small class="text-danger my-2">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Photo Profile -->
                <div class="mb-3">
                    <label for="formFile" class="form-label">Photo Profile:</label>
                    <input class="form-control" name="img_profile" type="file" id="formFile" accept=".jpg, .jpeg, .png">
                    @error('img_profile')
                        <small class="text-danger my-2">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" value="{{ old('email', $email) }}" placeholder="Email" name="email" class="form-control" required>
                    @error('email')
                        <small class="text-danger my-2">{{ $message }}</small>
                    @enderror
                </div>

                <!-- No Telepon -->
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon:</label>
                    <input type="text" id="no_telepon" value="{{ old('no_telepon', $noTelepon) }}" placeholder="No Telepon" name="no_telepon" pattern="[0-9]{7,13}" title="Masukkan angka antara 7 hingga 13 digit" class="form-control" required>
                    @error('no_telepon')
                        <small class="text-danger my-2">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Hanya untuk Role Admin -->
                @if (auth()->user()->id_role == 1)
                    <!-- Nama Usaha -->
                    <div class="mb-3">
                        <label for="nama_usaha" class="form-label">Nama Usaha:</label>
                        <input type="text" id="nama_usaha" value="{{ old('nama_usaha', $namaUsaha) }}" placeholder="Nama Usaha" name="nama_usaha" class="form-control" required>
                        @error('nama_usaha')
                            <small class="text-danger my-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat:</label>
                        <input type="text" id="alamat" value="{{ old('alamat', $alamat) }}" placeholder="Alamat" name="alamat" class="form-control" required>
                        @error('alamat')
                            <small class="text-danger my-2">{{ $message }}</small>
                        @enderror
                    </div>
                @endif

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role:</label>
                    <input type="text" id="role" value="{{ $role }}" placeholder="Role" readonly class="form-control">
                </div>

                <!-- Buttons -->
                <div class="row d-flex align-items-center justify-content-between">
                    <div class="pl-3 mb-3">                        
                        <button type="submit" class="btn btn-info" style="background-color: #0284c7">Simpan</button>
                    </div>
                    <div class="mb-3 pr-3">
                        <a href="{{ route('ganti-password') }}" class="" style="color: #0284c7;">Change Password</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
@endsection