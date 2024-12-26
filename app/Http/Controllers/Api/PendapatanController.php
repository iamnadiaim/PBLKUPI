<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendapatan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendapatanController extends Controller
{
    public function index() 
    {
        $products = Produk::all(); // Assuming you want to retrieve all products

        return response()->json($products); 
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'tanggal' => 'required',
                'nama_pembeli' => 'required|string|max:255',
                'id_produk' => 'required|exists:produks,id',
                'jumlah_produk' => [
                    'required',
                    'numeric',
                    'min:1',
                    function ($attribute, $value, $fail) use ($request) {
                        $product = Produk::find($request->id_produk);
                        if ($product && $value > $product->stok) {
                            $fail('Jumlah Produk Melebihi Stok Yang Tersedia');
                        }
                    },
                ],
                // Add other validation rules as needed
            ]);

            $user = auth()->user();

            $validatedData['id_usaha'] = $user->id_usaha;

            $product = Produk::findOrFail($validatedData['id_produk']);

            $validatedData['total'] = $product->harga * $validatedData['jumlah_produk'];
            $validatedData['harga_produk'] = $product->harga;

            $newStok = $product->stok - $validatedData['jumlah_produk'];

            $product->update(['stok' => $newStok]);

            Pendapatan::create($validatedData);

            DB::commit();

            return response()->json(['message' => 'Pendapatan Berhasil Ditambah']);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(['message' => 'Pendapatan Gagal Ditambah', 'error' => $th->getMessage()],400);
        }
    }
}
