<?php

namespace App\Services;

use Log;
use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;

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

    // ############# Get Attendance Report ############
public function getAbsentEmployees()
{
    // Get today's date
    $today = Carbon::today()->toDateString();
    $absentRecords = Attendance::join('employees', 'employees.id', '=', 'attendances.employee_id')
        ->join('users', 'users.id', '=', 'employees.user_id') 
        ->whereDate('attendances.date', $today)
        ->where('attendances.status', 'absent')
        ->get(['attendances.employee_id', 'users.name as employee_name', 'attendances.date', 'attendances.status']);
    $response = $absentRecords->map(function ($record) {
        return [
            'employee_id' => $record->employee_id,
            'name' => $record->employee_name,
            'date' => $record->date,
            'status' => $record->status,
        ];
    });

    return Helpers::result("Absent employees retrieved successfully", 200, $response);
}


}




