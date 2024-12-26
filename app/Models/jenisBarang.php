<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenisBarang extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function produks()
    {
        return $this->hasMany(Produk::class,'id_jenis_barang','id');
    }
}
