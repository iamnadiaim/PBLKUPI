<?php

namespace App\Http\Controllers;

use App\Models\jenisBarang;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        // Mengambil id_jenis_barang dari request
        $selectedJenisBarangId = $request->input('id_jenis_barang');

        $produks = Produk::where('id_usaha', auth()->user()->id_usaha)
            ->when($selectedJenisBarangId, function ($query, $selectedJenisBarangId) {
                return $query->where('id_jenis_barang', $selectedJenisBarangId);
            })
            ->get(); // Mengambil semua produk dari database yang sesuai dengan id_jenis_barang

        $jenis = jenisBarang::where('id_usaha', Auth::user()->usaha->id)
            ->orWhere('id_usaha', null)
            ->get();

        return view('produk.index', compact('produks', 'jenis', 'selectedJenisBarangId')); // Mengirimkan produk ke dalam view
    }

    public function show($id): View
    {
        $produk = Produk::findOrFail($id); // Mengambil produk berdasarkan ID
        return view('produk.show', compact('produk')); // Menampilkan detail produk ke dalam view
    }

    public function laporan(): View
    {
        $produks = Produk::all(); 
        return view('produk.tampilan', compact('produks'));
    }

    public function cetak(): View
    {
        $produks = Produk::all(); 
        return view('produk.cetak', compact('produks'));
    }

    public function createproduk(): View
    {
        $jenis_barangs = jenisBarang::where('id_usaha', Auth::user()->usaha->id)
            ->orWhere('id_usaha', null)
            ->get();

        return view('produk.create', compact('jenis_barangs'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'id_jenis_barang' => 'required',
            'ukuran' => 'required',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|numeric|min:1'
            // Sesuaikan validasi dengan kebutuhan Anda
        ]);

        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        $existingKodeProduk = Produk::where('kode_produk', $request->input('kode_produk'))
        ->where('id_usaha', auth()->user()->id_usaha)
        ->first();

    if ($existingKodeProduk) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['kode_produk' => 'Kode produk sudah ada. Silakan gunakan kode produk yang berbeda.']);
    }
        // Periksa apakah produk dengan nama dan ukuran yang sama sudah ada
        $existingProduk = Produk::where('nama_produk', $request->input('nama_produk'))
            ->where('ukuran', $request->input('ukuran'))
            ->where('id_usaha', auth()->user()->id_usaha)
            ->first();

        if ($existingProduk) {
            // Update the existing product's information if needed
            $existingProduk->update([
                'stok' => $existingProduk->stok + $request->input('stok'),    // Add stock if needed
            ]);

            return redirect()->route('produks.index')
                ->with('success', 'Produk berhasil diperbarui'); // Redirect ke halaman detail produk dengan pesan sukses
        } else {
            // Create a new product if no existing product is found
            $produk = Produk::create($validatedData);

            $activity = new \Spatie\Activitylog\Models\Activity();

                $activity->log_name = 'default'; 
                $activity->description = $produk->nama_produk;
                $activity->subject_type = get_class($produk);
                $activity->event = 'Menambahkan Produk';
                $activity->subject_id = $produk->id;
                $activity->causer_type = get_class(auth()->user());
                $activity->causer_id = auth()->id();
                $activity->entity_id = $produk->id; // Menyimpan ID produk
                $activity->entity_type = 'produk';   // Menyimpan tipe produk
                $activity->created_at = now();
                $activity->updated_at = now();
                $activity->save();

            return redirect()->route('produks.index',['highlight' => $produk->id])
                ->with('success', 'Produk berhasil ditambahkan'); // Redirect ke halaman detail produk dengan pesan sukses
        }
    }

    public function edit($id): View
    {
        $produk = Produk::findOrFail($id); // Mengambil produk berdasarkan ID
        $jenis_barangs = jenisBarang::where('id_usaha', Auth::user()->usaha->id)
            ->orWhere('id_usaha', null)
            ->get();

        return view('produk.edit', compact('produk', 'jenis_barangs')); // Menampilkan formulir untuk mengedit produk
    }

    public function update(Request $request, $id): RedirectResponse
{
    // Validasi data yang diterima dari formulir
    $validatedData = $request->validate([
        'nama_produk' => 'required',
        'ukuran' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|numeric|min:0' // Validasi stok baru
    ]);
    
    // Perbarui data produk yang ada di dalam database
    $produk = Produk::findOrFail($id);    
    
    // Hitung stok baru dengan menambahkan stok lama
    $stokBaru = $produk->stok + $request->input('stok');
    
    // Update data produk
    $produk->update([
        'nama_produk' => $validatedData['nama_produk'],
        'ukuran' => $validatedData['ukuran'],
        'harga' => $validatedData['harga'],
        'stok' => $stokBaru // Update stok dengan nilai baru yang ditambahkan
    ]);

    activity()
                ->causedBy(auth()->user()) 
                ->event($produk->nama_produk)
                ->log('Mengubah Data Produk');

    return redirect()->route('produks.index')
        ->with('success', 'Produk berhasil diperbarui'); // Redirect ke halaman detail produk dengan pesan sukses
}
    public function destroy($id): RedirectResponse
    {
        // Hapus data produk dari database
        $produk = Produk::findOrFail($id);
        $produk->delete();

        activity()
            ->causedBy(auth()->user()) 
            ->event($produk->nama_produk)
            ->log('Menghapus Produk');
        return redirect()->back()->with('destroy', 'Produk berhasil dihapus'); // Redirect ke halaman daftar produk dengan pesan sukses
    }
}
