<?php

namespace App\Services;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
use App\DTOs\AttendanceDTO;
use Illuminate\Support\Facades\Request;

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
        // Use DTO for the update case
        $attendanceDTO = new AttendanceDTO($employee->id, $today, Carbon::now(), 'present');
        $attendance->update($attendanceDTO->toArray());
    } else {
        // Use DTO for the create case
        $attendanceDTO = new AttendanceDTO($employee->id);
        Attendance::create($attendanceDTO->toArray());
    }

    return Helpers::result("Check-in recorded successfully", 200);
}

    // ############# Check-out ############
        public function checkOut(Employee $employee)
{
    $today = Carbon::today();
    $attendance = Attendance::where('employee_id', $employee->id)
                            ->whereDate('date', $today)
                            ->first();

    if ($attendance) {
        
        $checkOutTime = Carbon::now();
        $checkInTime = Carbon::parse($attendance->check_in_time);
        $workingSeconds = $checkInTime->diffInSeconds($checkOutTime);

        $hours = floor($workingSeconds / 3600);
        $minutes = floor(($workingSeconds % 3600) / 60);
        $seconds = $workingSeconds % 60;

        $workingHoursFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        $attendance->update([
            'check_out_time' => $checkOutTime,
            'working_hours' => $workingHoursFormatted, 
        ]);

        return Helpers::result("Check-out recorded successfully", 200);
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
    return "No check-in record found for today.";
}

}
