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
                <span style="font-size:18px;">Tambah Hutang Piutang </span>
                <div id="popup" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 10px; z-index: 999; margin-top: 165px">
                    <p><a href="{{ route('hutang.index') }}">Tambah Hutang Piutang</a></p>
                    <p><a href="{{ route('pembayaran.riwayathutang') }}">Riwayat Pembayaran</a></p>
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
                                <div class="toast-body text-light">
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
                                <div class="toast-body text-light">
                                    {{ session('success') }}
                                </div>
                                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                        @if (auth()->user()->role->nama_role == 'admin')
                            <div class="d-flex justify-content-between align-items-center">
                                <div class=" ">
                                    <a href="{{ route('hutang.index') }}" class="btn btn-primary">Hutang</a>  
                                    <a href="{{ route('piutang.index') }}" class="btn btn-secondary">Piutang</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead class="text-center bg-primary text-light">
                                <tr>
                                    <th scope="col">Tanggal Peminjaman</th>
                                    <th scope="col">Tanggal Jatuh Tempo</th>
                                    <th scope="col">Nama Pemberi Pinjaman</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Jumlah Angsuran</th>
                                    <th scope="col">Sisa Hutang</th>
                                    <th class="text-center" scope="col">Status</th>
                                    <th class="text-center" scope="col">Aksi</th>
                                </tr>
                            </thead>
                            @if (count($hutangs) > 0)
                            <tbody>
                                @foreach($hutangs as $hutang)
                                    <tr class= "text-center">
                                        <td>{{ $hutang->tanggal_pinjaman }}</td>
                                        <td>{{ $hutang->tanggal_jatuh_tempo }}</td>
                                        <td>{{ $hutang->nama }}</td>
                                        <td>{{ $hutang->catatan }}</td>
                                        <td>{{ $hutang->jumlah_hutang }}</td>
                                        <td>{{ $hutang->cicilan_terbayar }}/{{ $hutang->jumlah_cicilan }}</td>
                                        <td>{{ $hutang->sisa_hutang }}</td>
                                        <td class="status-cell">
                                            @if($hutang->status)
                                                Lunas
                                            @else
                                                Belum Lunas
                                            @endif
                                        </td>
                                        <td class="text-center">
                                        <a href="{{ route('bayarhutang.create', ['id' => $hutang->id]) }}" class="btn btn-primary">Bayar</a>
                       
                                        <!--  <a href="{{ route('pembayaran.hutang', $hutang->id) }}" class="btn btn-primary">Bayar</a>
                                    -->     
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @endif
                        </table>
                    </div>
                    
@if (count($hutangs) == 0)
    <p class="text-muted text-center" style="font-size: 20px;">Tidak Ada Hutang Yang Ditambahkan</p>
@endif
                </div>
            </div>
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
                var sisaHutang = parseInt(row.querySelector("td:nth-child(7)").textContent);
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
