<?php

namespace App\Listeners;

use App\Events\KurangiStokProduk;
use App\Models\Produk;
use App\Models\Pendapatan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class KurangiStokProdukListerner
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(KurangiStokProduk $event)
    {
        
    }
}
