<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\Usaha;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::insert([
            [
                "nama_role" => "admin"
            ],
            [
                "nama_role" => "pegawai"
            ]
        ]);

        $usaha = Usaha::create([
            "nama_usaha" => "Kue Kering Pandan",
            "alamat" => "Jalan. abc"
        ]);

        User::create([
            "id_role" => 1,
            "id_usaha" => $usaha->id,
            "nama" => 'admin',
            "email" => 'admin@gmail.com',
            "no_telepon" =>'081234567890',
            "password" =>'admin123',
        ]);

        User::create([
            "id_role" => 2,
            "id_usaha" => $usaha->id,
            "nama" => 'pegawai',
            "email" => 'pegawai@gmail.com',
            "no_telepon" =>'081999999999',
            "password" =>'pegawai123',
        ]);

        Kategori::insert([
            [
                "nama" => "Bahan Baku",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "nama" => "Peralatan",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "nama" => "Pajak",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "nama" => "Gaji Karyawan",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ]
        ]);
    }
}
