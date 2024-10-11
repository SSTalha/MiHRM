<?php

namespace App\Jobs\CronJobs\Salary;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class AddUnpaidSalariesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the current date (you can customize this if needed)
        $currentDate = Carbon::now()->toDateString();

        // Get all employees
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Check if an unpaid salary for this employee already exists on the current date
            $existingSalary = Salary::where('employee_id', $employee->id)
                ->where('status', 'unpaid')
                ->whereDate('created_at', $currentDate) // Check if already added today
                ->exists();

            // If no unpaid salary exists for the employee on the current date, create one
            if (!$existingSalary) {
                Salary::create([
                    'employee_id' => $employee->id,
                    'status' => 'unpaid',  // Default status is unpaid
                    'paid_date' => null,   // Unpaid salaries have no paid date
                    'created_at' => Carbon::now(),  // Track the date when the salary was added
                ]);
            }
        }
    }
}
