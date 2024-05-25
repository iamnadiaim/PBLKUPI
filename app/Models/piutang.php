<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class piutang extends Model
{
    use HasFactory;
    protected $table = 'piutangs';

    protected $fillable = [
        'tanggal_pinjaman','tanggal_jatuh_tempo', 'nama', 'catatan', 'jumlah_piutang','jumlah_cicilan', 'sisa_piutang', 'status', 'id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

}
