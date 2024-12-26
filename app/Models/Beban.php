<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beban extends Model
{
    protected $table = 'bebans';

    protected $fillable = [
        'tanggal', 'nama','kategori', 'jumlah', 'harga','id_kategori','id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori','id');
    }
}
