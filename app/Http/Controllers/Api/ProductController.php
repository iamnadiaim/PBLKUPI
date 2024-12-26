<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $produks = Produk::where('id_usaha', auth()->user()->id_usaha)->get(); // Mengambil semua produk dari database
        return response()->json($produks); // Mengirimkan produk ke dalam view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric|min:1'
            // Sesuaikan validasi dengan kebutuhan Anda
        ]);
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        
        $existingProduk = Produk::where('nama_produk', $request->input('nama_produk'))->where('id_usaha', auth()->user()->id_usaha)->first();

        if ($existingProduk) {
            // Update the existing product's information if needed
            $existingProduk->update([
                // 'harga' => $existingProduk->harga + $request->input('harga'), // Add prices if needed
                'stok' => $existingProduk->stok + $request->input('stok'),    // Add stock if needed
            ]);
            return response()->json(['message'=>'Produk Berhasil Diperbarui']); // Redirect ke halaman detail beban dengan pesan sukses
        } else {
            // Create a new product if no existing product is found
            Produk::create($validatedData);
           // Redirect ke halaman detail produk dengan pesan sukses
           return response()->json(['message'=>'Produk Berhasil Ditambah']); // Redirect ke halaman detail beban dengan pesan sukses
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
           ]);

        // Perbarui data produk yang ada di dalam database
        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return response()->json(['message' => 'Produk berhasil diperbarui']);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Hapus data produk dari database
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return response()->json(["message" => "Produk Berhasil dihapus"]); // Redirect ke halaman daftar produk dengan pesan sukses
    }
    
}
