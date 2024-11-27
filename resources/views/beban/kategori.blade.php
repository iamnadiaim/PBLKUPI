@extends('layouts.app')

@section('title', 'Tambah Kategori Pengeluaran')

@section('contents')

<div class="main">
    <div class="tanggal">
        <p id="tanggal"></p>
    </div>
    <div class="container">

        <form action="{{ route('kategori.store') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="kategori" class="form-label">Nama Kategori :</label>
                <input type="text" id="kategori" name="nama" required class="form-control" value="{{ old('nama') }}">
                @error('nama')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Tambah</button>
                <a href="{{ route('beban.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

@endsection
