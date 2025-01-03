@extends('layouts.app')

@section('title', 'Produk')

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

        <div class="box-container">
            <div class="container">
                <div class="mb-3">
                    @if (session()->has('destroy'))
                    <div class="d-flex absolute justify-content-end">
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

                    <a href="{{ route('produks.create') }}" class="btn btn-success mr-3" style="margin-top: 10px">Tambah +</a>
                </div>
                @if ($produks->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Kode Produk</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Jenis Barang</th>
                                <th scope="col">Ukuran</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th class="text-center" scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $produk)
                            <tr id="Produk-{{ $produk->id }}"> <!-- Tambahkan ID ke baris -->
                                <td>{{ $produk->kode_produk }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->jenisBarang->nama }}</td>
                                <td>{{ $produk->ukuran }}</td>
                                <td>{{ $produk->harga }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td class="action-buttons d-flex justify-content-center">
                                    <a href="{{ route('produks.edit', $produk->id) }}" class="btn btn-primary">Edit</a>
                                    <form class="d-inline ml-3" action="{{ route('produks.destroy', $produk->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <h3 class="text-center text-secondary">Tidak Ada Produk</h3>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var url = new URL(window.location.href);
        // Ambil ID dari parameter 'highlight'
        var highlightId = url.searchParams.get('highlight'); 
        console.log('Highlight ID:', highlightId); // Debugging

        if (highlightId) { // Pastikan ID tidak kosong
            var highlightedProduk = document.querySelector('#Produk-' + highlightId); // Menggabungkan string
            console.log(highlightedProduk); // Debugging untuk melihat apakah elemen ditemukan

            if (highlightedProduk) { // Pastikan elemen ditemukan
                highlightedProduk.scrollIntoView({ behavior: 'smooth' });
                highlightedProduk.style.backgroundColor = '#b5d5d0'; // Ganti dengan warna yang diinginkan
            } else {
                console.error('Element with ID "Produk-' + highlightId + '" not found.');
            }
        }
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myToast = new bootstrap.Toast(document.getElementById('myToast'));
        myToast.show();
    });
</script>

@endsection
