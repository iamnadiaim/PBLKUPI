<?php

namespace App\Http\Controllers;

use App\Models\jenisBarang;
use App\Models\Produk;
use Illuminate\Http\Request;

class jenisBarangController extends Controller
{
    public function index()
    {
        // return view('beban.kategori');
        $produk = Produk::all();
        $jenis_barangs = jenisBarang::all();

        return view('produk.jenisBarang', compact('produk', 'jenis_barangs'));
    }
    public function store(Request $request)
    {
    $validate = $request->validate([
        "nama" => [
            "required",
            "regex:/^[a-zA-Z\s]+$/", // Mengizinkan huruf dan spasi saja
            "not_regex:/^\d+$/", // Tidak boleh hanya angka
            "unique:jenis_barangs,nama" // Validasi unik untuk kolom nama
        ]
    ], [
        "nama.required" => "Jenis Produk harus diisi.",
        "nama.regex" => "format tidak sesuai",
        "nama.not_regex" => "Jenis Produk tidak sesuai format.",
        "nama.unique" => "Data sudah ada." // Pesan error jika data sudah ada
    ]);

    $validate['id_usaha'] = auth()->user()->id_usaha;

    jenisBarang::create($validate);
    return redirect()->route('produks.create')
        ->with('tambah', 'Kategori berhasil ditambahkan');
    }


}
