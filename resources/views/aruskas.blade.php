@extends('layouts.app') 

@section('title', 'Arus Kas')

@section('contents')

<body>
    <div class="container mt-4">
        <div class="row d-flex justify-content-between align-items-end">
            <div class="mt-3 col-4 my-3">
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
            </div>
            <div class="text-right">
                @if (request('month'))
                            <p>{{ strtoupper(request('month')) }} {{ \Carbon\Carbon::now()->format('Y') }}</p>
                            @else
                            <p>{{  strtoupper(\Carbon\Carbon::now()->format('F Y')) }}</p>
                            @endif
                            <div class="col d-flex justify-content-end align-items-center margin-left">
            <a href="{{ route('cetakaruskas', ["month" => request("month")]) }}" target="_blank" class="btn bg-warning text-dark">Cetak</a>
        </div>
            </div>
        </div>
    <div class="container mt-3">
        <main>
            <section class="aktivitas-operasional">
                <h5 class="bg-primary text-light p-2">Aktivitas Operasional</h5>
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="">Kas yang dikeluarkan:</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $kategori)
                                <tr>
                                    <td>{{ $kategori->nama }}:</td>
                                    <td class="text-right">{{ $kategori->bebans->sum('harga') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td class="text-right"><strong>{{ $kategoris->sum(function($kategori) { return
                                            $kategori->bebans->sum('harga'); }) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="width: 100%;height: 2px; background-color: black; border-radius: 20px"></div>

                <div class="row mt-3">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th >Kas yang diterima:</th>
                                    <th class="text-right">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Total Pendapatan</strong></td>
                                    <td class="text-right"><strong>{{ $totalPendapatan }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                </section>

                <section class="saldo">
                <h5 class="ml-3 font-weight-bold">Saldo</h5>
                <div class= "d-flex justify-content-between">
                <p class= "bg-primary text-center text-light p-2 col-5 ml-3">Saldo Awal </p>
                <div class="col-6" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc">
                    <p class="text-center mt-2 text-dark">Rp. {{ $cekSaldo->saldo }} </p>
                </div>
                </div>
                <div class= "d-flex justify-content-between">
                    <p class= "bg-primary text-center text-light p-2 col-5 ml-3">Saldo Akhir </p>
                    <div class="col-6" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc">
                        <p class="text-center mt-2 text-dark">Rp. {{ $saldoAkhir }} </p>
                    </div>
                    </div>
            </section>
        </main>
    </div>
</body>
@endsection
