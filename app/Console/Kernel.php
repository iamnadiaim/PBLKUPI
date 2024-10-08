<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
<<<<<<< HEAD
        $schedule->command('notif:bayarHutang')->dailyAt("14:43");  
        $schedule->command('notif:bayarPiutang')->dailyAt("14.43");
=======
        $schedule->command('notif:bayarHutang')->dailyAt("00:00");  
        $schedule->command('notif:bayarPiutang')->everyMinute();
>>>>>>> 0dff5f6901ee860ee09d5f359bfe9388e543fa81

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
