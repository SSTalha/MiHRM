<?php

namespace App\Console;

use App\Jobs\CronJobs\HandleLeave;
use App\Jobs\CronJobs\PaySalaryJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\CronJobs\Salary\PaySalariesJob;
use App\Jobs\CronJobs\Salary\AddUnpaidSalariesJob;
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
    $schedule->job(new AddUnpaidSalariesJob())->everyMinute();
    $schedule->job(new PaySalariesJob())->everyTwoMinutes();

    
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
