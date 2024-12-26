<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $table = 'pendapatans';

    protected $guarded = ['id'];

    public function produk()
    {
        // return $this->hasMany(Produk::class);
        return $this->belongsTo(Produk::class,'id_produk','id');
    }
}
