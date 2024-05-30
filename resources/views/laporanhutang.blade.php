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
                        <div class="d-flex justify-content-between">
    <div>
        <div class="income-report">
            <form action="" method="GET">
                <label for="month">Pilih Bulan:</label>
                <div class="d-flex">
                    <select class="form-control" id="month" name="month">
                        @foreach($bulan as $bul)
                            @if(request('month') == strtolower($bul['inggris']))
                                <option value="{{ strtolower($bul['inggris']) }}" selected>{{ $bul['indo'] }}</option>
                            @else
                                <option value="{{ strtolower($bul['inggris']) }}">{{ $bul['indo'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary ml-3">Lihat</button>
                </div>
            </form>
            <div class="income-label mr-1 d-flex mt-3">
                <div class="income-label mr-5 ">Periode Laporan:</div>
                @if (request('month'))
                <p>{{ strtoupper(request('month')) }} {{ \Carbon\Carbon::now()->format('Y') }}</p>
                @else
                <p>{{  strtoupper(\Carbon\Carbon::now()->format('F Y')) }}</p>
                @endif
            </div>
        </div>
    </div>
        <section class="container">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('print-pegawai') }}" target="_blank" class="btn btn-warning">Cetak</a>
            </div>
            <div class="d-flex justify-content-end">
                <p class="bg-danger text-center text-light p-2 col-2 ml-auto mr-4">Total Hutang</p>
                <div class="col-3" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc">
                    <p class="text-left mt-2 text-dark">Rp. {{ $totalHutang }}</p>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <p class="bg-success text-center text-light p-2 col-2 ml-auto mr-4">Total Piutang</p>
                <div class="col-3" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc">
                    <p class="text-left mt-2 text-dark">Rp. {{ $totalPiutang }}</p>
                </div>
            </div>
        </section>
</div>
                        </div>

                    </div>
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
