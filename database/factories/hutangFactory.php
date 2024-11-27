<?php

namespace Database\Factories;

use App\Models\hutang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HutangFactory extends Factory
{
    protected $model = hutang::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Menghasilkan tanggal pinjaman acak dalam satu tahun terakhir
        $tanggalPinjaman = $this->faker->dateTimeBetween('-1 year', 'now');
        // Menghitung tanggal jatuh tempo 1 bulan setelah tanggal pinjaman
        $tanggalJatuhTempo = (clone $tanggalPinjaman)->modify('+1 month');

        return [
            'tanggal_pinjaman' => Carbon::instance($tanggalPinjaman)->format('Y-m-d'), // Tanggal pinjaman dalam format Y-m-d
            'tanggal_jatuh_tempo' => Carbon::instance($tanggalJatuhTempo)->format('Y-m-d'), // Tanggal jatuh tempo dalam format Y-m-d
            'nama' => $this->faker->name,
            'catatan' => $this->faker->sentence,
            'jumlah_hutang' => $this->faker->numberBetween(100000, 1000000), // Nilai hutang antara 100,000 - 1,000,000
            'jumlah_cicilan' => $this->faker->numberBetween(1, 36), // Jumlah cicilan antara 1 - 5 kali
            'sisa_hutang' => $this->faker->numberBetween(0, 100000), // Sisa piutang acak
            'status' => $this->faker->boolean, // Status acak (true/false)
            'id_usaha' => $this->faker->numberBetween(1, 10), // ID usaha acak antara 1 dan 10
        ];
    }
}
