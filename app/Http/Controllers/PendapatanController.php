<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Events\KurangiStokProduk;
use App\Models\User;
use Exception;

class PendapatanController extends Controller
{
    public function index():View
    {
        $products = Produk::where('id_usaha', auth()->user()->id_usaha)->get(); // Assuming you want to retrieve all products

        return view('pendapatan', compact('products')); // Mengirimkan pendapatan ke dalam view
    }
    public function pendapatan()
    {
        // Logika untuk menampilkan formulir pembuatan pendapatan
        return view('pendapatan'); 

    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'id_produk' => 'required|exists:produks,id', // Make sure the product exists // Make sure the product exists
            'jumlah_produk' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    // Custom validation rule to check if the quantity is greater than the available stock
                    $product = Produk::find($request->id_produk);
                    if ($product && $value > $product->stok) {
                        $fail('Jumlah Produk Melebihi Stok Yang Tersedia');
                    }
                },
            ],
            'nama_pembeli' => 'required|string|max:255', 
    // Other fields and rules...
            // Sesuaikan validasi dengan kebutuhan Anda
        ]);
    
        $product = Produk::find($validatedData['id_produk']);

        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        
        $validatedData['harga_produk'] = $product->harga;

        $validatedData['total'] = $product->harga * $validatedData['jumlah_produk'];
    
        $stok = $product->stok - $validatedData['jumlah_produk'];
    
        $product->update([
            'stok' => $stok
        ]);
    
        // Simpan data pendapatan baru ke dalam database
        $pendapatan = Pendapatan::create($validatedData);
        $activity = new \Spatie\Activitylog\Models\Activity();

        $activity->log_name = 'default'; 
        $activity->description = 'Pendapatan';
        $activity->subject_type = get_class($pendapatan);
        $activity->event = 'Menambahkan Transaksi';
        $activity->subject_id = $pendapatan->id;
        $activity->causer_type = get_class(auth()->user());
        $activity->causer_id = auth()->id();
        $activity->entity_id = $pendapatan->id; // Menyimpan ID produk
        $activity->entity_type = 'pendapatan';   // Menyimpan tipe produk
        $activity->created_at = now();
        $activity->updated_at = now();
        $activity->save();
    
        // event(new KurangiStokProduk($produk, $jumlah_produk));
    
        return redirect()->route('riwayat.index', ['highlight' => $pendapatan->id])
        ->with('success', 'Transaksi baru berhasil ditambahkan'); // Redirect ke halaman detail pendapatan dengan pesan sukses
    }
    

    // public function edit($id):View
    // {
    //     $pendapatan = Pendapatan::findOrFail($id); // Mengambil pendapatan berdasarkan ID
    //     return view('pendapatan.edit', compact ('pendapatan')); // Menampilkan formulir untuk mengedit pendapatan
    // }

    // public function update(Request $request, $id): RedirectResponse
    // {
    //     // Validasi data yang diterima dari formulir
    //     $validatedData = $request->validate([
    //         'nama_pendapatan' => 'required',
    //         'harga' => 'required|numeric',
    //         'stok' => 'required|numeric|min:0'
    //         // Sesuaikan validasi dengan kebutuhan Anda
    //     ]);

    //     // Perbarui data pendapatan yang ada di dalam database
    //     $pendapatan = Pendapatan::findOrFail($id);
    //     $pendapatan->update($request->all());

    //     return redirect()->route('pendapatans.index')
    //         ->with('success', 'Produk berhasil diperbarui'); // Redirect ke halaman detail pendapatan dengan pesan sukses
    // }

    // public function destroy($id): RedirectResponse
    // {
    //     // Hapus data pendapatan dari database
    //     $pendapatan = Pendapatan::findOrFail($id);
    //     $pendapatan->delete();

    //     return redirect()->route('pendapatans.index')
    //         ->with('success', 'Produk berhasil dihapus'); // Redirect ke halaman daftar pendapatan dengan pesan sukses
    // }
}
