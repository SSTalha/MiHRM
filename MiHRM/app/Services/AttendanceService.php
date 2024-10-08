<?php

namespace App\Services;

use App\Helpers\Helpers;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceService
{
        // ############# Check-in ############

    public function checkIn(Employee $employee)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('date', $today)
                                ->first();

        if ($attendance) {
            $attendance->update([
                'check_in_time' => Carbon::now(),
                'status' => 'present',
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in_time' => Carbon::now(),
                'status' => 'present',
            ]);
        }

        return Helpers::result("Check-in recorded successfully",200);
    }

    // ############# Check-out ############
    public function checkOut(Employee $employee)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('date', $today)
                                ->first();

        if ($attendance) {            
           $attendance->update([
                'check_out_time' => Carbon::now(),
            ]);

            return Helpers::result("Check-out recorded successfully",200);
        }

        return "No check-in record found for today.";
    }
}
