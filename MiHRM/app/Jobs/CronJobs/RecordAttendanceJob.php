<?php

namespace App\Jobs\CronJobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class RecordAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all employees
        $employees = Employee::all();
        $today = Carbon::today();

        foreach ($employees as $employee) {
            // Create an attendance record with status 'absent' for all employees
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'status' => 'absent',
                'check_in_time' => '00:00:00'
            ]);
        }
    }
}
