<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Console\Command;

class UpdateAttendenceRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:attendence-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employees = Employee::all();
        $today = Carbon::today();

        foreach ($employees as $employee) {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'status' => 'absent',
                'check_in_time' => '00:00:00'
            ]);
        }
    }
}
