<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\KurangiStokProduk;
use App\Listeners\KurangiStokProdukListener;
use App\Listeners\KurangiStokProdukListerner;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        KurangiStokProduk::class => [
            KurangiStokProdukListerner::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
