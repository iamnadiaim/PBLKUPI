@extends('layouts.app')

@section('title', 'Riwayat Pengeluaran')

@section('contents')

<div class="main">
    <div class="tanggal">
        <p id="tanggal"></p>
    </div>

    <div class="mb-3">
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
        <a href="{{ route('riwayat.index') }}" class="btn btn-secondary">Pendapatan</a>            
        <a href="{{ route('riwayatbeban') }}" class="btn btn-primary ">Pengeluaran</a>
    </div>	

    <div class="mb-3">
      <h5 class="text-lg font-semibold">Total Beban</h5>
      <span style="background-color: #de4a4a; padding: 10px; color: #fff;" class="px-4 py-2 rounded-lg font-bold text-xl">
          {{'Rp ' .$totalBeban}}
      </span>
  </div>
    {{-- <div class="input-group mb-3">
		<div class="col-3">
			<input type="text" class="form-control" id="startDate" placeholder="dd/mm/yy">
		</div>
		<div class="col-3">
			<input type="text" class="form-control" id="endDate" placeholder="dd/mm/yy">
		</div>
    </div> --}}

    <table class="table table-striped table-bordered">
        <thead class="bg-primary text-light">
            <tr>
                <th>Tanggal Transaksi</th>
                <th>Kategori</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        @if (count($beban) > 0)
        <tbody>
            @foreach($beban as $b)
            <tr>
                <td>{{ $b->tanggal }}</td>
                <td>{{ $b->kategori->nama }}</td>
                <td>{{ $b->nama }}</td>
                <td>{{ $b->jumlah}}</td>
                <td>{{ $b->harga }}</td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>
    @if (count($beban) == 0)
    <p class="text-muted text-center" style="font-size: 20px;">Tidak Ada Transaksi Yang Ditambahkan</p>
@endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var myToast = new bootstrap.Toast(document.getElementById('myToast'));
    myToast.show();
  });
</script>
@endsection
