@extends('layouts.app')

@section('title', 'Pendapatan')

@section('contents')

    <form action="{{ route('pendapatan.store') }}" class="signup-form" method="POST">
        @csrf
        <div class="row m-auto">
            <!-- Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tanggal">Tanggal :</label><br>
                    <input type="date" placeholder="Tanggal" class="form-control form-control-md" required name="tanggal"
                        value="{{ old('tanggal') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    @error('tanggal')
                        <p class="text-red">Tanggal tidak boleh lebih dari hari ini.</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_pembeli">Nama Pembeli :</label><br>
                    <input type="text" placeholder="Nama Pembeli" class="form-control" required name="nama_pembeli"
                        value="{{ old('nama_pembeli') }}">
                    @error('nama_pembeli')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_produk">Pilih Produk :</label><br>
                    <select name="id_produk" required class="form-control">
                        <option value="" disabled selected>Select a product</option>
                        @foreach ($products as $product)
                            @if ($product->stok > 0)
                                <option value="{{ $product->id }}"
                                    {{ old('id_produk') == $product->id ? 'selected' : '' }}>
                                    {{ $product->nama_produk }} {{ $product->ukuran }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('id_produk')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jumlah_produk">Jumlah :</label><br>
                    <input type="number" placeholder="Jumlah Produk" class="form-control" required name="jumlah_produk">
                    @error('jumlah_produk')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            <div class="mb-3 col-md-12" style="text-align: center;">
                <input type="submit" class="btn btn-primary" value="Tambah Transaksi">
            </div>
        </div>
    </form>

@endsection
