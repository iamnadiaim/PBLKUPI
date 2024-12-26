<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayarHutang extends Model
{
    use HasFactory;
    protected $table = 'bayar_hutangs';

    protected $fillable = [
        'id_hutang','tanggal_pembayaran', 'nama', 'pembayaran','jumlah','id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

    public function hutang()
    {
        return $this->belongsTo(hutang::class, 'id_hutang');
    }
}
