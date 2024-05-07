<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayarPiutang extends Model
{
    use HasFactory;
    protected $table = 'bayar_piutangs';

    protected $fillable = [
        'id_piutang','tanggal_pembayaran', 'nama', 'pembayaran','jumlah','id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

    public function piutang()
    {
        return $this->belongsTo(hutang::class, 'id_piutang');
    }
}
