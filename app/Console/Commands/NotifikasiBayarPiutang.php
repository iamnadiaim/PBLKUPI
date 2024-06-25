<?php

namespace App\Console\Commands;
use App\Models\piutang;
use App\Notifications\PeringatanBayar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifikasiBayarPiutang extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:bayarPiutang';

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
        
        $piutangList = piutang::where('tanggal_jatuh_tempo', $besok)->get();
        foreach ($piutangList as $piutang) {
            $user = $piutang->usaha; // Asumsikan hutang memiliki relasi user
            Notification::send($user, new PeringatanBayar($piutang));
            $this->info("Notifikasi " . $piutang->nama . ' telah dikirim untuk piutang yang jatuh tempo besok.');
        }

    }

}
