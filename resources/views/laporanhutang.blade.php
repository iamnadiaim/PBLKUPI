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
                    <div id="popup" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 10px; z-index: 999; margin-top: 165px">
                    <p><a href="{{ route('hutang.index') }}">Tambah Hutang Piutang</a></p>
                    <p><a href="#">Riwayat Pembayaran</a></p>
                    <p><a href="{{ route('laporanhutang.index') }}">Laporan</a></p>
                </div>
                </div>
            </div>
            <div style="position: fixed; bottom: 75px; right: 110px;">
                <a href="{{ route('hutang.create') }}" class="btn btn-success" style="position: relative;">
                    <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #4E73DF; color: white; width: 50px; height: 50px; border-radius: 70%; display: flex; justify-content: center; align-items: center;">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
            </div>
            <div class="box-container">
                <div class="container">
                    <div class="mb-3">
                            @if (session()->has('destroy'))
                            <div class="d-flex justify-content-end">
                            <div class="toast my-4 bg-danger" id="myToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="15000">
                                <div class="toast-header bg-danger text-light justify-content-between">
                                <div class="toast-body text-ligth">
                                    {{ session('destroy') }}
                                </div>
                                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                            </div>
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

                            <div class="income-report">
                                <label for="periode">Periode:</label>
                                <input type="date" id="periode" value="{{ $selectedDate->format('Y-m-d') }}" min="{{ $selectedDate->subMonth()->format('Y-m-d') }}" max="{{ $selectedDate->endOfMonth()->format('Y-m-d') }}">
                                <button onclick="ubahPeriode()">Ubah</button>
                            </div>
                            <div class="income-summary">
                                <div class="income-label">Tanggal Laporan:</div>
                                <div class="income-value">{{ $selectedDate->format('d F Y') }}</div>
                            </div>
                            
    <table class="table table-bordered" >
        <thead class="bg-primary text-light">
            <tr>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th><span class="text-danger">Memberi</span></th>
                <th><span class="text-success">Menerima</span></th>

                
            </tr>
        </thead>
        <tbody>
            @foreach($hutangs as $hutang)
            <tr>
                <td>{{ $hutang->tanggal_pinjaman }}</td>
                <td>{{ $hutang->nama }}</td>
                <td>
                    @if($hutang->jumlah_hutang > 0)
                    <span class="text-danger">{{ $hutang->jumlah_hutang }}</span>
                    @else
                    {{ $hutang->jumlah_hutang }}
                    @endif
                </td>
                <td>
                    @if($piutang->jumlah_piutang< 0)
                    <span class="text-success">{{ abs($hutang->jumlah_hutang) }}</span>
                    @else
                    0
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

                    </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
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