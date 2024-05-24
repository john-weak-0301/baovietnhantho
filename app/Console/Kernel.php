<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $logPath = storage_path('logs/backups.log');

        $schedule
            ->command('post:update-count')
            ->daily()
            ->at('00:30');

        $schedule
            ->command('backup:clean')
            ->sundays()
            ->at('01:00')
            ->appendOutputTo($logPath);

        $schedule
            ->command('backup:run')
            ->sundays()
            ->at('02:00')
            ->appendOutputTo($logPath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
