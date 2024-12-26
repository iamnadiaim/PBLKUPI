<?php

namespace Database\Factories;

use App\Models\piutang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PiutangFactory extends Factory
{
    protected $model = piutang::class;
    
    public function definition()
    {
        // Menghasilkan tanggal pinjaman acak dalam satu tahun terakhir
        $tanggalPinjaman = $this->faker->dateTimeBetween('-1 year', 'now');
        
        // Menghitung tanggal jatuh tempo 1 bulan setelah tanggal pinjaman
        $tanggalJatuhTempo = (clone $tanggalPinjaman)->modify('+1 month');
        
        return [
            'id_usaha' => $this->faker->numberBetween(1, 10), // ID usaha acak antara 1 dan 10
            'nama' => $this->faker->name, // Nama acak
            'catatan' => $this->faker->text(50), // Catatan acak
            'jumlah_piutang' => $this->faker->numberBetween(1000, 1000000), // Jumlah piutang acak
            'jumlah_cicilan' => $this->faker->numberBetween(1, 12), // Jumlah cicilan acak (1 hingga 12 bulan)
            'tanggal_pinjaman' => Carbon::instance($tanggalPinjaman)->format('Y-m-d'), // Tanggal pinjaman dalam format Y-m-d
            'tanggal_jatuh_tempo' => Carbon::instance($tanggalJatuhTempo)->format('Y-m-d'), // Tanggal jatuh tempo dalam format Y-m-d
            'sisa_piutang' => $this->faker->numberBetween(0, 100000), // Sisa piutang acak
            'status' => $this->faker->boolean, // Status acak (true/false)
        ];
    }
}