<?php

namespace App\Console\Commands;
use App\Models\hutang;
use App\Notifications\PeringatanBayar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;



class NotifikasiBayarHutang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:bayarHutang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $besok = now()->addDay()->toDateString();
        
        $hutangList = hutang::where('tanggal_jatuh_tempo', $besok)->get();
        foreach ($hutangList as $hutang) {
            $user = $hutang->usaha; // Asumsikan hutang memiliki relasi user
            Notification::send($user, new PeringatanBayar($hutang));
            $this->info("Notifikasi " . $hutang->nama . ' telah dikirim untuk hutang yang jatuh tempo besok.');
        }

    }
}
