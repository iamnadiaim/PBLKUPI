@extends('layouts.app')

@section('title', 'Ganti Kata Sandi')

@section('contents')

<body>
    <div class="container">
        <div class="main-body">

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card mb-3">
                        <div class="card-body">
                            @if (session()->has('error'))
                            <div class="text-danger text-center my-3">
                                {{session('error') }}
                            </div>
                            @endif
                            <form action="{{ route('gantiPassword') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="old_password" class="form-label">Kata Sandi Lama</label>
                                    <input type="password" id="old_password" name="old_password" class="form-control">
                                    @error('old_password')
                                        {{ $message }}
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control">
                                    @error('new_password')
                                        {{ $message }}
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                    @error('confirm_password')
                                        {{ $message }}
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

@endsection
