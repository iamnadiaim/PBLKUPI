<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'id_usaha', 'kode_produk', 'nama_produk','id_jenis_barang', 'ukuran', 'harga', 'stok'
        // Kolom lain yang ingin diisi secara massal
    ];

    // Contoh relasi dengan model lain
    public function orders()
    {
        return $this->hasMany(Pendapatan::class);
    }
    public function jenisBarang()
    {
        return $this->belongsTo(jenisBarang::class, 'id_jenis_barang');
    }
}