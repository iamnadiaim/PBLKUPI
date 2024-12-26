@extends('layouts.app')

@section('title', 'Piutang')

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
        <form action="{{ route('piutang.store') }}" method="post">
            @csrf
            <div class="row justify-content-center">
                {{-- Kiri --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_pinjaman">Tanggal Peminjaman :</label><br>
                        <input type="date" class="form-control form-control-md" placeholder="Tanggal Pinjaman" required
                            name="tanggal_pinjaman"
                            value="{{ old('tanggal_pinjaman') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('tanggal_pinjaman')
                            <p class="text-red">Tanggal pinjaman tidak boleh lebih dari hari ini.</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo :</label><br>
                        <input type="date" class="form-control form-control-md" placeholder="Tanggal Jatuh Tempo"
                            required name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('tanggal_jatuh_tempo')
                            <p class="text-red">Tanggal jatuh tempo harus setelah atau sama dengan hari ini.</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Customer:</label>
                        <input type="text" id="nama" name="nama" required class="form-control"
                            value="{{ old('nama') }}">
                        @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jumlah_piutang">Nominal:</label>
                        <input type="number" id="jumlah_piutang" name="jumlah_piutang" required class="form-control"
                            value="{{ old('jumlah_piutang') }}">
                        @error('jumlah_piutang')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah_cicilan">Jumlah Cicilan:</label>
                        <input type="number" id="jumlah_cicilan" name="jumlah_cicilan" required class="form-control"
                            value="{{ old('jumlah_cicilan') }}">
                        @error('jumlah_cicilan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan:</label>
                        <textarea id="catatan" name="catatan" rows="5" required class="form-control">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>
            <!-- Tombol Submit -->
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Tambah Piutang</button>
                <a href="{{ route('piutang.index') }}" class="btn btn-danger">Batal</a>
            </div>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalPinjamanInput = document.getElementById('tanggal_pinjaman');
            const tanggalJatuhTempoInput = document.getElementById('tanggal_jatuh_tempo');

            // Update min date for tanggal_jatuh_tempo when tanggal_pinjaman changes
            tanggalPinjamanInput.addEventListener('change', function() {
                tanggalJatuhTempoInput.min = tanggalPinjamanInput.value;
            });

            // Initialize min value on page load based on current tanggal_pinjaman
            tanggalJatuhTempoInput.min = tanggalPinjamanInput.value;

            // Show toast notification
            const toastElement = document.getElementById('myToast');
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        });
    </script>
@endsection
