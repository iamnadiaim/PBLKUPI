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
                    <span style="font-size:18px;">Riwayat Pembayaran </span>
                    <div id="popup" style="display: none; position: absolute; background-color: #fff; border: 1px solid #ccc; padding: 10px; z-index: 999; margin-top: 165px">
                    <p><a href="{{ route('hutang.index') }}">Tambah Hutang Piutang</a></p>
                    <p><a href="#">Riwayat Pembayaran</a></p>
                    <p><a href="#">Laporan</a></p>
                </div>
                </div>
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
                                        <a href="{{ route('pembayaran.riwayathutang') }}" class="btn btn-primary">Hutang</a>  
                                        <a href="{{ route('pembayaran.riwayatpiutang') }}" class="btn btn-secondary">Piutang</a>
                                    </div>
                                    <div class="mt-3 col-md-4 ml-auto my-35">
                <form action="" method="GET">
                    <div class="d-flex justify-content-end">
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
            </div>
                                </div>
                                
                            @endif

                    </div>
                   
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th scope="col">Tanggal Pembayaran</th>
                                <th scope="col">Nama Pemberi Pinjaman</th>
                                <th scope="col">Cara Pembayaran</th>
                                <th scope="col">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bayarhutang as $BayarHutang)
                                <tr>
                                    <td>{{ $BayarHutang->tanggal_pembayaran }}</td>
                                    <td>{{ $BayarHutang->nama }}</td>
                                    <td>{{ $BayarHutang->pembayaran }}</td>
                                    <td>{{ $BayarHutang->jumlah }}</td>
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