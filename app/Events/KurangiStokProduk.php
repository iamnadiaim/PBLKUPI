<?php

namespace App\Events;

use App\Models\Produk;
use App\Models\Pendapatan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KurangiStokProduk implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $produk;
    public $jumlah_produk;

    public function __construct(Produk $produk, string $jumlah_produk)
    {
        $this->produk = $produk;
        $this->jumlah_produk = $jumlah_produk;
    }

    
    public function broadcastOn()
    {
        return new Channel('');
    }
}
