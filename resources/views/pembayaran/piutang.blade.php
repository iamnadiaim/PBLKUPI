@extends('layouts.app')

@section('title', 'Pembayaran Piutang')

@section('contents')

<div class="container">
<div class="container">
  @if (session()->has('error'))
    <div class="alert alert-danger" style="font-family: Arial, sans-serif;">
        {{ session('error') }}
    </div>
  @endif
  
  @if (session()->has('success'))
    <div class="d-flex justify-content-end">
      <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="15000">
        <div class="toast-header bg-primary text-light justify-content-between">
          <div class="toast-body text-ligth">
            {{ session('success') }}
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
      <form action="{{ route('bayarpiutang.store') }}" method="post">
        @csrf
        <div>
          <label for="tanggal_pinjaman">Tanggal Pembayaran:</label><br>
          <input type="date" placeholder="Tanggal Pembayaran" required name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
          @error('tanggal_pembayaran')
            <p class="text-red">Tanggal pinjaman tidak boleh lebih dari hari ini.</p>
          @enderror
        </div>

        <label for="nama">Nama Peminjam:</label><br>
        <input type="text" id="nama" name="nama" required class="form-control" value="{{ old('nama') }}">
        @error('nama')
          <span class="error">{{ $message }}</span>
        @enderror

        <label for="pembayaran">Cara Pembayaran:</label><br>
        <input type="text" id="pembayaran" name="pembayaran" required class="form-control" value="{{ old('pembayaran') }}">
        @error('pembayaran')
          <span class="error">{{ $message }}</span>
        @enderror

        <label for="jumlah">Nominal :</label><br>
        <input type="number" id="jumlah" name="jumlah" required class="form-control" min="0" value="{{ old('jumlah') }}">
        @error('jumlah')
          <span class="error">{{ $message }}</span>
        @enderror


        <div class="mt-3" style="text-align: left;">
          <input type="submit" class="btn btn-primary" value="Simpan">
          <a href="{{ route('piutang.index') }}" class="btn btn-danger">Batal</a>
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