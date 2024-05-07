<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hutang extends Model
{
    use HasFactory;
    protected $table = 'hutangs';

    protected $fillable = [
        'tanggal_pinjaman','tanggal_jatuh_tempo', 'nama', 'jumlah_hutang','jumlah_cicilan', 'sisa_hutang', 'id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

}
