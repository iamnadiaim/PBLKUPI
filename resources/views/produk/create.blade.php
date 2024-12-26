@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('contents')
    <style>
        /* Custom margin hanya untuk PC (Desktop) */
        @media (min-width: 768px) {
            .mt-custom-pc {
                margin-top: 1.95rem;
                /* Nilai margin custom */
            }
        }
    </style>
    <div class="container mt-4">
        <form action="{{ route('produks.store') }}" method="post" class="needs-validation" novalidate>
            @csrf

            <div class="row">
                {{-- Kode Produk --}}
                <div class="col-md-6 mb-3">
                    <label for="kode_produk" class="form-label">Kode Produk:</label>
                    <input type="text" id="kode_produk" name="kode_produk" class="form-control" required>
                    @error('kode_produk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama Produk --}}
                <div class="col-md-6 mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk:</label>
                    <input type="text" id="nama_produk" name="nama_produk" class="form-control" required>
                    @error('nama_produk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Barang --}}
                <div class="col-md-6 mb-3">
                    <label for="id_jenis_barang">Jenis Barang:</label>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('jenisBarang.index') }}" class="btn btn-sm btn-link">Tambah Jenis Barang +</a>
                    </div>
                    <select name="id_jenis_barang" required class="form-control">
                        <option value="" disabled selected>Select a Jenis Barang</option>
                        @foreach ($jenis_barangs as $jenisBarang)
                            <option value="{{ $jenisBarang->id }}"
                                {{ old('id_jenis_barang') == $jenisBarang->id ? 'selected' : '' }}>
                                {{ $jenisBarang->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ukuran --}}
                <div class="col-md-6 mb-3 mt-custom-pc">
                    <label for="ukuran" class="form-label">Ukuran:</label>
                    <input type="text" id="ukuran" name="ukuran" class="form-control" required>
                    @error('ukuran')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="col-md-6 mb-3">
                    <label for="harga" class="form-label">Harga:</label>
                    <input type="number" id="harga" name="harga" class="form-control" required>
                    @error('harga')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stok --}}
                <div class="col-md-6 mb-3">
                    <label for="stok" class="form-label">Stok:</label>
                    <input type="number" id="stok" name="stok" class="form-control" required>
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="col-12 text-center flex mt-4">
                <button type="submit" class="btn btn-primary">Tambah Produk</button>
                <a href="{{ route('produks.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection
