<?php

namespace Database\Factories;

use App\Models\BayarPiutang;
use App\Models\piutang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BayarPiutangFactory extends Factory
{
    protected $model = BayarPiutang::class;

    public function definition()
    {
        // Mengambil piutang yang sudah ada atau membuat yang baru
        $piutang = piutang::factory()->create();

        return [
            'id_piutang' => $piutang->id, // Menghubungkan pembayaran dengan ID piutang
            'tanggal_pembayaran' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'), // Tanggal pembayaran dalam rentang 1-30 hari lalu
            'nama' => $this->faker->name, // Nama acak
            'pembayaran' => $this->faker->randomElement(['cash', 'transfer', 'kartu kredit']), // Metode pembayaran acak
            'jumlah' => $this->faker->numberBetween(10000, 1000000), // Jumlah acak antara 10,000 hingga 1,000,000
            'id_usaha' => $piutang->id_usaha, // ID usaha sesuai dengan piutang terkait
        ];
    }
}
