<?php

namespace App\Jobs\CronJobs\Salary;

use App\Models\Salary;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class PaySalariesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all unpaid salaries
        $salaries = Salary::where('status', 'unpaid')->get();

        foreach ($salaries as $salary) {
            $salary->update([
                'status' => 'paid',              // Update status to paid
                'paid_date' => Carbon::now(),    // Set the current date as paid date
            ]);
        }
    }
}
