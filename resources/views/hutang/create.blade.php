@extends('layouts.app')

@section('title', 'Hutang')

@section('contents')

<div class="container">
  @if (session()->has('tambah'))
    <div class="d-flex justify-content-end">
      <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="15000">
        <div class="toast-header bg-primary text-light justify-content-between">
          <div class="toast-body text-ligth">
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
      <form action="{{ route('hutang.store') }}" method="post">
        @csrf
        <div>
          <label for="tanggal_pinjaman">Tanggal Peminjaman :</label><br>
          <input type="date" placeholder="Tanggal Pinjaman" required name="tanggal_pinjaman" value="{{ old('tanggal_pinjaman') ?? \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
          @error('tanggal_pinjaman')
              <p class="text-red">Tanggal pinjaman tidak boleh lebih dari hari ini.</p>
          @enderror
      </div>
      

        <div>
          <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo :</label><br>
          <input type="date" placeholder="Tanggal Jatuh Tempo" required name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
          @error('tanggal_jatuh_tempo')
            <p class="text-red">Tanggal jatuh tempo harus setelah atau sama dengan hari ini.</p>
          @enderror
        </div>

        <label for="nama">Nama Pemberi Pinjaman :</label><br>
        <input type="text" id="nama" name="nama" required class="form-control" value="{{ old('nama') }}">
        @error('nama')
            @if ($message == 'required')
                <p class="text-danger">Nama harus diisi.</p>
            @elseif ($message == 'min.string')
                <p class="text-danger">Nama harus diisi dengan minimal 3 karakter.</p>
            @elseif ($message == 'max.string')
                <p class="text-danger">Nama tidak boleh lebih dari 60 karakter.</p>
            @else
                <p class="text-danger">{{ $message }}</p>
            @endif
        @enderror

        <label for="jumlah_hutang">Nominal :</label><br>
        <input type="number" id="jumlah_hutang" name="jumlah_hutang" required class="form-control" min="0" value="{{ old('jumlah_hutang') }}">
        @error('jumlah_hutang')
        @if ($message == 'validation.required')
                <p class="text-danger">Nominal harus diisi.</p>
            @elseif ($message == 'validation.min.string')
                <p class="text-danger">Nominal harus diisi dengan minimal 1000</p>
            @elseif ($message == 'validation.max.string')
                <p class="text-danger">Nominal tidak boleh lebih dari 10.000.000</p>
            @else
                <p class="text-danger">{{ $message }}</p>
            @endif
        @enderror

        <label for="jumlah_cicilan">Jumlah Angsuran :</label><br>
        <input type="number" id="jumlah_cicilan" name="jumlah_cicilan" required class="form-control" min="0" value="{{ old('jumlah_cicilan') }}">
        @error('jumlah_cicilan')
        @if ($message == 'validation.required')
                <p class="text-danger">Nominal harus diisi.</p>
            @elseif ($message == 'validation.min.string')
                <p class="text-danger">Nominal harus diisi dengan minimal 1 kali</p>
            @elseif ($message == 'validation.max.string')
                <p class="text-danger">Nominal tidak boleh lebih dari 60 kali</p>
            @else
                <p class="text-danger">{{ $message }}</p>
            @endif
        @enderror

        <label for="nama">Catatan  :</label><br>
        <textarea type="text" rows="5" id="catatan" name="catatan" required class="form-control" value="{{ old('catatan') }}" ></textarea>
        @error('catatan')
        @if ($message == 'required')
                <p class="text-danger">Nama harus diisi.</p>
            @elseif ($message == 'min.string')
                <p class="text-danger">Nama harus diisi dengan minimal 10 karakter.</p>
            @elseif ($message == 'max.string')
                <p class="text-danger">Nama tidak boleh lebih dari 255 karakter.</p>
            @else
                <p class="text-danger">{{ $message }}</p>
            @endif
        @enderror

        <div class="mt-3" style="text-align: left;">
          <input type="submit" class="btn btn-primary" value="Tambah Hutang">
          <a href="{{ route('hutang.index') }}" class="btn btn-danger">Batal</a>
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