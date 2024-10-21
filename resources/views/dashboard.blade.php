@extends('layouts.app')

@if (auth()->user()->role->nama_role == 'admin')
@section('title', "Hai, Admin $namaUsaha 🙌")
@else
@section('title', "Halo, Pegawai $namaUsaha 🖐️ ")
@endif

@section('contents')

@if (auth()->user()->role->nama_role == 'admin')
<div class="col-6">
    <form action="{{ route('saldo.store') }}" method="post"> <!-- Tambahkan method="post" untuk mengirimkan form -->
        @csrf <!-- Tambahkan CSRF token untuk keamanan form -->
        <div class="d-flex align-items-end justify-content-center mb-3">
            <label for="stok" class="flex-shrink-0 mr-2" style="color: black;">Modal Awal Usaha</label>
            <input type="text" id="stok" name="saldo" class="form-control mr-2" placeholder="Masukkan " @if ($cekSaldo) value="{{ number_format($cekSaldo->saldo, 0, ',', '.') }}" readonly @endif min="0" required>
            @error('saldo')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            @if (!$cekSaldo)
                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Data Anda Sudah Benar ??')">OKE</button> <!-- Tambahkan type="submit" pada tombol -->
            @endif  
        </div>
    </form>
</div>
@endif

<style>
@import url(https://fonts.googleapis.com/css?family=Roboto);

body {
    font-family: Roboto, sans-serif;
}

.pt-4 {
    padding-top: 0 !important;
}

.card {
    margin-bottom: 20px;
}

.footer {
    background: #f8f9fa;
    padding: 10px;
    text-align: center;
}
</style>

<div class="row @if(auth()->user()->role->nama_role == 'pegawai') d-flex justify-content-center @endif">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kasMasukFormat }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kasKeluarFormat }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role->nama_role == 'admin')
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Laba Rugi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allLabaRugiFormat }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pembelian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendapatan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (auth()->user()->id_role == 1)
<!-- Row untuk Grafik Produk Terlaris dan Arus Kas -->
<div class="row">
    <!-- Card untuk Grafik Produk Terlaris (rata kiri) -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Produk Terlaris</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <div id="produkPieChart"></div> <!-- Tempat untuk menampilkan grafik produk -->
                </div>
            </div>
        </div>
    </div>
    <!-- Card untuk Grafik Arus Kas -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Arus Kas</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <div id="arusKasChart"></div> <!-- Tempat untuk menampilkan grafik arus kas -->
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>    
document.addEventListener('DOMContentLoaded', function () {
    // Data Produk Terlaris
    let produkTerlaris = @json($produkTerlaris);
    
    // Menghitung total jumlah terjual
    let totalProdukTerlaris = produkTerlaris.reduce((sum, produk) => sum + produk.total_terjual, 0); 

    // Grafik Donut untuk Produk Terlaris
    if (totalProdukTerlaris > 0) {
    let seriesProduk = produkTerlaris.map(produk => produk.total_terjual); 
    let labelsProduk = produkTerlaris.map(produk => produk.nama_produk); 

    var produkOptions = {
        series: seriesProduk,
        chart: {
            type: 'donut',
        },
        labels: labelsProduk,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 200 },
                legend: { position: 'bottom' }
            }
        }]
    };

    var produkPieChart = new ApexCharts(document.querySelector("#produkPieChart"), produkOptions);
    produkPieChart.render();
    } else {
        document.getElementById("produkPieChart").innerHTML = "Belum Ada Data Produk Terlaris";
    }


    
    // Grafik Arus Kas
    let kasMasukData = @json($chartOptions['series'][0]['data']);
    let kasKeluarData = @json($chartOptions['series'][1]['data']);
    let categories = @json($chartOptions['xaxis']['categories']);

    if (kasMasukData.length > 0 || kasKeluarData.length > 0) {
        var arusKasOptions = {
            series: [{
                name: 'Kas Masuk',
                data: kasMasukData
            }, {
                name: 'Kas Keluar',
                data: kasKeluarData
            }],
            chart: {
                type: 'line',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: categories,
            }
        };

        var arusKasChart = new ApexCharts(document.querySelector("#arusKasChart"), arusKasOptions);
        arusKasChart.render();
    } else {
        document.getElementById("arusKasChart").innerHTML = "Belum Ada Data Arus Kas";
    }
});
</script>
@endsection
