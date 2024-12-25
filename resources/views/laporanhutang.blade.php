@extends('layouts.app')
@section('title', 'Hutang Piutang')
@section('contents')

    <div class="main-container">
        <div class="navcontainer">
            <!-- Navigation section -->
            {{-- Your navigation code goes here --}}
        </div>

        <div class="main">
            <div class="tanggal">
                <p id="tanggal"></p>
            </div>
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 30px;">
                    <a href="#" id="toggleIcon" onclick="togglePopup()" style="text-decoration: none;">
                        <span style="margin-right: 10px;"><i class="fa fa-angle-down"></i></span>
                    </a>
                    <span style="font-size:18px;">Laporan</span>
                    <div id="popup"
                        style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 10px; z-index: 999; margin-top: 165px">
                        <p><a href="{{ route('hutang.index') }}">Tambah Hutang Piutang</a></p>
                        <p><a href="{{ route('pembayaran.riwayathutang') }}">Riwayat Pembayaran</a></p>
                        <p><a href="{{ route('laporanhutang.index') }}">Laporan</a></p>
                    </div>
                </div>
            </div>

            <div class="box-container">
                <div class="container">
                    <div class="mb-3">
                        @if (session()->has('destroy'))
                            <div class="d-flex justify-content-end">
                                <div class="toast my-4 bg-danger" id="myToast" role="alert" aria-live="assertive"
                                    aria-atomic="true" data-delay="15000">
                                    <div class="toast-header bg-danger text-light justify-content-between">
                                        <div class="toast-body text-ligth">
                                            {{ session('destroy') }}
                                        </div>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (session()->has('success'))
                            <div class="d-flex justify-content-end">
                                <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive"
                                    aria-atomic="true" data-delay="15000">
                                    <div class="toast-header bg-primary text-light justify-content-between">
                                        <div class="toast-body text-ligth">
                                            {{ session('success') }}
                                        </div>
                                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class=" mt-2">
                            <div class="row gy-3">
                                <!-- Bagian Kiri -->
                                <div class="col-md-6">
                                    <form action="" method="GET"
                                        class="d-flex flex-column flex-md-row align-items-center gap-2">
                                        <label for="month" class="form-label mb-0 mr-1">Periode :</label>
                                        <select class="form-control mr-1 col-12 col-md-6" id="month" name="month">
                                            @foreach ($bulan as $bul)
                                                <option value="{{ strtolower($bul['inggris']) }}"
                                                    {{ request('month') == strtolower($bul['inggris']) ? 'selected' : '' }}>
                                                    {{ $bul['indo'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="btn mt-1 col-12 col-md-auto btn-primary">Lihat</button>
                                    </form>

                                    <!-- Periode -->
                                    <div class="income-summary d-flex flex-row mt-2 gap-2">
                                        <label class="fw-bold mr-1">Periode :</label>
                                        <p class="mb-0">
                                            {{ request('month') ? request('month') . ' ' . \Carbon\Carbon::now()->format('Y') : \Carbon\Carbon::now()->format('F') }}
                                        </p>
                                    </div>

                                    <!-- Tanggal Dibuat -->
                                    <div class="income-summary d-flex flex-column flex-md-row mt-2 gap-2">
                                        <label class="fw-bold">Tanggal Dibuat : </label>
                                        <p class="mb-0">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                    </div>
                                </div>

                                <!-- Bagian Kanan -->
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end mb-3 mt-3 mt-md-0 flex-column flex-md-row">
                                        <a href="{{ route('print-laporan', ['month' => request('month')]) }}"
                                            target="_blank" class="btn btn-warning w-auto w-md-100">
                                            Cetak
                                        </a>
                                    </div>


                                    <!-- Total Hutang -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                                        <div class="bg-danger text-white text-center py-2 flex-fill me-md-2">
                                            Total Hutang
                                        </div>
                                        <div class="bg-light border text-center py-2 flex-fill">
                                            Rp. {{ $totalHutang }}
                                        </div>
                                    </div>

                                    <!-- Total Piutang -->
                                    <div class="d-flex flex-column flex-md-row justify-content-between">
                                        <div class="bg-success text-white text-center py-2 flex-fill me-md-2">
                                            Total Piutang
                                        </div>
                                        <div class="bg-light border text-center py-2 flex-fill">
                                            Rp. {{ $totalPiutang }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th><span class="text-danger">Memberi</span></th>
                                <th><span class="text-success">Menerima</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hutangs as $hutang)
                                <tr>
                                    <td>{{ $hutang->tanggal_pinjaman }}</td>
                                    <td>{{ $hutang->nama }}</td>
                                    <td>
                                        @if ($hutang->jumlah_hutang > 0)
                                            <span class="text-danger">{{ $hutang->jumlah_hutang }}</span>
                                        @else
                                            {{ $hutang->jumlah_hutang }}
                                        @endif
                                    </td>
                                    <td>0</td>
                                    <!-- Kolom "Menerima" selalu menampilkan 0 karena data jumlah piutang hanya ditampilkan di loop berikutnya -->
                                </tr>
                            @endforeach
                            {{-- {{dd($piutangs)}} --}}
                            @foreach ($piutangs as $piutang)
                                <tr>
                                    <td>{{ $piutang->tanggal_pinjaman }}</td>
                                    <td>{{ $piutang->nama }}</td>
                                    <td>0</td>
                                    <!-- Kolom "Memberi" selalu menampilkan 0 karena data jumlah hutang hanya ditampilkan di loop sebelumnya -->
                                    <td>
                                        @if ($piutang->jumlah_piutang > 0)
                                            <span class="text-success">{{ $piutang->jumlah_piutang }}</span>
                                        @else
                                            {{ $piutang->jumlah_piutang }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-primary text-light">
                                <td colspan="2" class="text-center font-weight-bold">Total</td>
                                <td class="text-danger font-weight-bold">Rp. {{ $totalHutang }}</td>
                                <td class="text-success font-weight-bold">Rp. {{ $totalPiutang }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myToast = new bootstrap.Toast(document.getElementById('myToast'));
                myToast.show();
            });
            document.addEventListener("DOMContentLoaded", function() {
                var rows = document.querySelectorAll("tbody tr");

                rows.forEach(function(row) {
                    var sisaHutang = parseInt(row.querySelector("td:nth-child(6)").textContent);
                    var statusCell = row.querySelector(".status-cell");

                    if (sisaHutang === 0) {
                        statusCell.textContent = "Lunas";
                        statusCell.style.color = "green";
                    } else {
                        statusCell.textContent = "Belum Lunas";
                        statusCell.style.color = "red";
                    }
                });
            });

            function togglePopup() {
                var popup = document.getElementById("popup");
                if (popup.style.display === "none") {
                    popup.style.display = "block";
                } else {
                    popup.style.display = "none";
                }
            }
        </script>
    @endsection
