<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Salary;
use Illuminate\Console\Command;

class PaySalaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pay-salaries';

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
        $salaries = Salary::where('status', 'unpaid')->get();

        foreach ($salaries as $salary) {
            $salary->update([
                'status' => 'paid',
                'paid_date' => Carbon::now(),
            ]);
        }
    }
}
