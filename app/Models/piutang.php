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

      // Method to count the number of installments paid
      public function getCicilanTerbayarAttribute()
      {
          return $this->bayarPiutangs()->count();
      }
  
      // Relation to BayarPiutang
      public function bayarPiutangs()
      {
          return $this->hasMany(BayarPiutang::class, 'id_piutang');
      }

      public function usaha()
      {
          return $this->belongsTo(Usaha::class,'id_usaha');
      }
}