@extends('layouts.app')

@section('title', 'Tambah Jenis Barang')

@section('contents')

<div class="main">
    <div class="tanggal">
        <p id="tanggal"></p>
    </div>
    <div class="container">

        <form action="{{ route('jenisbarangs.store') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="jenis_barang" class="form-label">Nama Jenis Barang :</label>
                <input type="text" id="jenis_barang" name="nama" required class="form-control">
                @error('nama')
                    <div class="text-danger">
                        {{-- Menampilkan pesan kesalahan sesuai validasi --}}
                        @if ($message === "Jenis barang tidak sesuai format.")
                            Jenis barang tidak sesuai format.
                        @elseif ($message === "Format jenis barang tidak boleh mengandung karakter spesial.")
                            Format jenis barang tidak boleh mengandung karakter spesial.
                        @elseif ($message === "Data sudah ada.")
                            Data sudah ada.
                        @else
                            {{ $message }}
                        @endif
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Tambah</button>
                <a href="{{ route('produks.create') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

@endsection
