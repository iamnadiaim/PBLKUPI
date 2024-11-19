@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('contents')
<div class="container">
    @if (session()->has('tambah'))
    <div class="d-flex justify-content-end">
      <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="15000">
        <div class="toast-header bg-primary text-light justify-content-between">
          <div class="toast-body text-light">
            {{ session('tambah') }}
          </div>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('beban.store') }}" method="post">
                @csrf

                <!-- Tanggal -->
                <div>
                    <label for="tanggal">Tanggal :</label><br>
                    <input type="date" placeholder="Tanggal" required name="tanggal" value="{{ old('tanggal') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    @error('tanggal')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <label for="kategori">Kategori :</label><br>
                <a href="{{ route('kategori.index') }}" class="tambah-kategori">Tambah Kategori +</a>
                <select name="id_kategori" required class="form-control">
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('id_kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <p class="text-danger">{{ $message }}</p>
                @enderror

                <!-- Nama -->
                <label for="nama">Nama :</label><br>
                <input type="text" id="nama_produk" name="nama" value="{{ old('nama') }}" required class="form-control">
                @error('nama')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <!-- Jumlah -->
                <label for="jumlah">Jumlah :</label><br>
                <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required class="form-control" min="1">
                @error('jumlah')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <!-- Harga -->
                <label for="harga">Harga :</label><br>
                <input type="number" id="harga" name="harga" value="{{ old('harga') }}" required class="form-control" min="1000">
                @error('harga')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <!-- Submit Button -->
                <div class="mt-3" style="text-align: center;">
                    <input type="submit" class="btn btn-primary" value="Tambah">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myToast = new bootstrap.Toast(document.getElementById('myToast'));
        myToast.show();
    });
</script>
@endsection

