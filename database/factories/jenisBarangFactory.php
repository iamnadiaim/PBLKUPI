<?php

namespace Database\Factories;

use App\Models\jenisBarang;
use Illuminate\Database\Eloquent\Factories\Factory;

class jenisBarangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = jenisBarang::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->words(2, true), // Nama jenis barang unik
            'id_usaha' => 1, // Atur id_usaha default, sesuaikan sesuai kebutuhan
        ];
    }
}
