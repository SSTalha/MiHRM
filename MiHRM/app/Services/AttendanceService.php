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

        // ############# Get Attendance Report ############
    public function getEmployeesAttendence($date = null, $status)
    {
        $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString;

        $absentRecords = Attendance::with(['employee.user'])
            ->whereDate('date', $targetDate)
            ->where('status', $status)
            ->get();  
        $response = $absentRecords->map(function($record){
            $employee = $record->employee;
            $user = $employee ? $employee->user : null;
            return [
                'employee_id' => $record->employee_id,
                'name' => $record ? $user->name : null,
                'date' => $record->date,
                'status' => $record->status,
            ];
        });
        return Helpers::result("Absent employees retrieved successfully", 200, $response);
    }
}
