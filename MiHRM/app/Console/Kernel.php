<?php

namespace App\Console;

use App\Jobs\CronJobs\HandleLeave;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\MakeService::class,
        Commands\MakeHelper::class,
        Commands\MakeDTO::class,
     ];
     
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->job(new \App\Jobs\CronJobs\RecordAttendanceJob())->everyMinute();
    $schedule->job(new HandleLeave())->everyMinute();
    
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
