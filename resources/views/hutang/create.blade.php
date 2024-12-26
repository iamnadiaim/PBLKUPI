@extends('layouts.app')

@section('title', 'Hutang')

@section('contents')

    <div class="container">
        @if (session()->has('tambah'))
            <div class="d-flex justify-content-end">
                <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive" aria-atomic="true"
                    data-delay="15000">
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
        <form action="{{ route('hutang.store') }}" method="post">
            @csrf
            <div class="row justify-content-center">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <!-- Tanggal Peminjaman -->
                    <div class="form-group">
                        <label for="tanggal_pinjaman">Tanggal Peminjaman :</label><br>
                        <input type="date" class="form-control form-control-md" placeholder="Tanggal Pinjaman" required name="tanggal_pinjaman"
                            value="{{ old('tanggal_pinjaman') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('tanggal_pinjaman')
                            <p class="text-red">Tanggal pinjaman tidak boleh lebih dari hari ini.</p>
                        @enderror
                    </div>

                    <!-- Tanggal Jatuh Tempo -->
                    <div class="form-group">
                        <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo :</label><br>
                        <input type="date" class="form-control form-control-md" placeholder="Tanggal Jatuh Tempo" required name="tanggal_jatuh_tempo"
                            value="{{ old('tanggal_jatuh_tempo') }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('tanggal_jatuh_tempo')
                            <p class="text-red">Tanggal jatuh tempo harus setelah atau sama dengan hari ini.</p>
                        @enderror
                    </div>

                    <!-- Nama Pemberi Pinjaman -->
                    <div class="form-group">
                        <label for="nama">Nama Pemberi Pinjaman :</label><br>
                        <input type="text" id="nama" name="nama" required class="form-control"
                            value="{{ old('nama') }}">
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <!-- Nominal Hutang -->
                    <div class="form-group">
                        <label for="jumlah_hutang">Nominal :</label><br>
                        <input type="number" id="jumlah_hutang" name="jumlah_hutang" required class="form-control"
                            min="0" value="{{ old('jumlah_hutang') }}">
                        @error('jumlah_hutang')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Jumlah Angsuran -->
                    <div class="form-group">
                        <label for="jumlah_cicilan">Jumlah Angsuran :</label><br>
                        <input type="number" id="jumlah_cicilan" name="jumlah_cicilan" required class="form-control"
                            min="0" value="{{ old('jumlah_cicilan') }}">
                        @error('jumlah_cicilan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div class="form-group">
                        <label for="catatan">Catatan :</label><br>
                        <textarea type="text" rows="5" id="catatan" name="catatan" required class="form-control"
                            value="{{ old('catatan') }}"></textarea>
                        @error('catatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                <!-- Tombol Submit dan Batal -->
                <div class="mt-3 col-md-12" style="text-align: center;">
                    <input type="submit" class="btn btn-primary" value="Tambah Hutang">
                    <a href="{{ route('hutang.index') }}" class="btn btn-danger">Batal</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myToast = new bootstrap.Toast(document.getElementById('myToast'));
            myToast.show();
        });
    </script>
@endsection
