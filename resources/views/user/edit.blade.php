@extends('layouts.app')

@section('title', 'Edit Profile')

@section('contents')

<div class="main">
    <div class="tanggal">
        <p id="tanggal"></p>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-8eb">
            <form action="{{route('editProfile')}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" id="nama" value="{{ $nama }}" placeholder="Nama" name="nama" class="form-control">
                </div>

                <div class="mb-3">
                  <label for="formFile" class="form-label">Photo Profile</label>
                  <input class="form-control" name="img_profile" type="file" id="formFile">
                  @error('img_profile')
                      <div class="text-danger my-2">
                        {{ $message }}
                      </div>
                  @enderror
                </div>              

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" id="email" value="{{ $email }}" placeholder="Email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon:</label>
                    <input type="text" id="no_telepon" value="{{ $noTelepon }}" placeholder="No Telepon" name="no_telepon" pattern="[0-9]{12,13}" title="Please enter between 12 and 13 digits" class="form-control">
                </div>

                @if (auth()->user()->id_role == 1)
                
                <div class="mb-3">
                    <label for="nama_usaha" class="form-label">Nama Usaha:</label>
                    <input type="text" id="nama_usaha" value="{{ $namaUsaha }}" placeholder="Nama Usaha" name="nama_usaha" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat:</label>
                    <input type="text" id="alamat" value="{{ $alamat }}" placeholder="Alamat" name="alamat" class="form-control">
                </div>

                @endif

                <div class="mb-3">
                    <label for="role" class="form-label">Role:</label>
                    <input type="text" id="role" value="{{ $role }}" placeholder="Role" readonly class="form-control">
                </div>

                
                <div class="row d-flex align-items-center justify-content-between">
                    <div class="pl-3 mb-3">                        
                        <button type="submit" class="btn btn-info" style="background-color: #0284c7">Simpan</button>
                    </div>
                    <div class="mb-3 pr-3">
                        <a href="{{route('ganti-password')}}" class="" style="color: #0284c7;">Ganti Password</a>
                    </div>
                </div>
          
            </form>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
@endsection
