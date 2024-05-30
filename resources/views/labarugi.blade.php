    @extends('layouts.app')

    @section('title', 'Laba Rugi')

    @section('contents')

    <body>
        <div class="container mt-">
        <div class="mt-3 col-4 my-3">
        <form action="" method="GET">
    <div class="d-flex justify-content-end align-items-center">
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
<div class="col d-flex justify-content-end align-items-center">
            <a href="{{ route('cetaklabarugi') }}" target="_blank" class="btn bg-warning text-dark">Cetak</a>
        </div>

            <div class="row d-flex justify-content-center mb-5">
                <div class="col-md-9">
                    <div class="card bg-light mb-6">
                        <div class="card-header text-center border">
                            <h5>TOKO {{ $namaUsaha }}</h5>
                            @if (request('month'))
                            <p>{{ strtoupper(request('month')) }} {{ \Carbon\Carbon::now()->format('Y') }}</p>
                            @else
                            <p>{{  strtoupper(\Carbon\Carbon::now()->format('F Y')) }}</p>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="bg-primary text-light">Pendapatan</h5>
                                    <div class="total-pendapatan d-flex justify-content-between">
                                        <span>Total Pendapatan</span>
                                        <span>{{ $totalPendapatan }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5 class="bg-primary text-light">Pengeluaran</h5>
                                    <div class="card-body">
                                        @foreach ($kategoris as $kategori)
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $kategori->nama }}:</span>
                                                <span>
                                                {{
                                                        $kategori->bebans
                                                            // ->whereYear('created_at', $selectedDate->year)
                                                            // ->whereMonth('created_at', $selectedDate->month)
                                                            ->sum('harga');
                                                        }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                        <div class="p-1 d-flex bg-primary text-light align-items-center justify-content-between">
                                            <h5 class="">Total Beban</h5>
                                            <h5>{{ $totalBeban }}</h5>
                                        </div>
                                    
                                    <div class="total-beban ">
                                    </div>
                                    <div class="mt-3 total-beban d-flex justify-content-between">
                                        <h5>
                                            @if ($labaRugi >= 0)
                                                Laba Bersih
                                            @else
                                                Rugi
                                            @endif
                                        </h5>
                                        <h5>{{ $labaRugi }}</h5>
                                    </div>
                                 
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </body>

    @endsection
