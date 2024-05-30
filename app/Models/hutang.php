<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hutang extends Model
{
    use HasFactory;
    protected $table = 'hutangs';

    protected $fillable = [

        'tanggal_pinjaman','tanggal_jatuh_tempo', 'nama', 'catatan', 'jumlah_hutang','jumlah_cicilan', 'sisa_hutang', 'status', 'id_usaha'
        // Kolom lain yang ingin diisi secara massal
    ];

    // Method to count the number of installments paid
    public function getCicilanTerbayarAttribute()
    {
        return $this->bayarHutangs()->count();
    }

    // Relation to BayarHutang
    public function bayarHutangs()
    {
        return $this->hasMany(BayarHutang::class, 'id_hutang');
    }

}