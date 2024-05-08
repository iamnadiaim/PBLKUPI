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
                    <p><a href="{{ route('pembayaran.riwayatpiutang') }}">Riwayat Pembayaran</a></p>
                    <p><a href="#">Laporan</a></p>
                </div>
                </div>
            </div>
            <div style="position: fixed; bottom: 75px; right: 110px;">
                <a href="{{ route('piutang.create') }}" class="btn btn-success" style="position: relative;">
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

                            @if (auth()->user()->role->nama_role == 'admin')
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('hutang.index') }}" class="btn btn-secondary">Hutang</a>  
                                        <a href="{{ route('piutang.index') }}" class="btn btn-primary">Piutang</a>
                                    </div>
                                    <div>
                                        <!-- <a href="{{ route('piutang.create') }}" class="btn btn-success">Tambah</a> -->
                                        <a href="{{ route('pembayaran.piutang') }}" class="btn btn-primary">Bayar Piutang</a>
                                        
                                    </div>
                                </div>
                            @endif
                    </div>
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th scope="col">Tanggal Peminjaman</th>
                                <th scope="col">Tanggal Jatuh Tempo</th>
                                <th scope="col">Nama Peminjam</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Jumlah Cicilan</th>
                                <th scope="col">Sisa Piutang</th>
                                <th class="text-center" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($piutangs as $piutang)
                                <tr>
                                    
                                    <td>{{ $piutang->tanggal_pinjaman}}</td>
                                    <td>{{ $piutang->tanggal_jatuh_tempo }}</td>
                                    <td>{{ $piutang->nama }}</td>
                                    <td>{{ $piutang->jumlah_piutang }}</td>
                                    <td>{{ $piutang->jumlah_cicilan }}</td>
                                    <td>{{ $piutang->sisa_piutang }}</td>
                                    <td class="status-cell"></td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
