<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\MakeService::class,
        Commands\MakeHelper::class,
        Commands\MakeDTO::class,
        Commands\UpdateAttendenceRecord::class,
        Commands\HandleLeave::class,
        Commands\AddUnpaidSalary::class,
        Commands\PaySalaries::class,
     ];
     
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{

    $schedule->command( "command:attendence-record")->everyMinute();
    $schedule->command( "command:handle-leave")->everyMinute();
    $schedule->command( "command:add-unpaid-salary")->everyMinute();
    $schedule->command( "command:pay-salaries")->everyTwoMinutes();
    

    
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