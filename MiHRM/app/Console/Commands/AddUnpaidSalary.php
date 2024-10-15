<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Salary;
use App\Models\Employee;
use Illuminate\Console\Command;

class AddUnpaidSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add-unpaid-salary';

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
        $currentDate = Carbon::now()->toDateString();
        $employees = Employee::all();

        foreach ($employees as $employee) {

            $existingSalary = Salary::where('employee_id', $employee->id)
                ->where('status', 'unpaid')
                ->whereDate('created_at', $currentDate)
                ->exists();

            if (!$existingSalary) {
                Salary::create([
                    'employee_id' => $employee->id,
                    'status' => 'unpaid',
                    'paid_date' => null,
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }

}
