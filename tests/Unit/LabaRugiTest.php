<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabaRugiTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first(); 
        $this->actingAs($this->user); 
    }

    /**
     * TC-Laba-01: User melihat laporan laba rugi untuk periode tertentu.
     */
    public function test_view_laporan_laba_rugi()
    {
        $response = $this->get('/labarugi?month=2023-11'); 

        $response->assertStatus(200);
        $response->assertViewIs('labarugi'); 
        $response->assertViewHas(['totalPendapatan', 'totalBeban', 'labaRugi']); 
    }

    /**
     * TC-Laba-02: User mencetak laporan laba rugi.
     */
    public function test_print_laporan_laba_rugi()
    {
        $response = $this->get('/print-labarugi?month=2023-11'); 

        $response->assertStatus(200);
        $response->assertViewIs('cetaklabarugi'); 
        $response->assertViewHas(['totalPendapatan', 'totalBeban', 'labaRugi']); 
    }
}
