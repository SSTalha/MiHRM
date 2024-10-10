<?php

namespace App\Services\Employee;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
use App\DTOs\EmployeeDTOs\AttendanceDTO;
use Symfony\Component\HttpFoundation\Response;

class AttendanceService
{
    /**
     * /check in
     * @param \App\Models\Employee $employee
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkIn(Employee $employee)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('date', $today)
                                ->first();
        if ($attendance) {
            $attendanceDTO = new AttendanceDTO($employee->id, $today, Carbon::now(), 'present');
            $attendance->update($attendanceDTO->toArray());
        
        } else {
            $attendanceDTO = new AttendanceDTO($employee->id);
            Attendance::create($attendanceDTO->toArray());
        }
        return Helpers::result("Check-in recorded successfully", Response::HTTP_OK);
    }

    /**
     * Summary of checkOut
     * @param \App\Models\Employee $employee
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkOut(Employee $employee)
    {
        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('date', $today)
                                ->first();  // Changed get() to first() for single record

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
        }
        return Helpers::result("Check-out recorded successfully", Response::HTTP_OK);
    }

    /**
     * Get Employees Attendance
     * @param mixed $date
     * @param mixed $status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getEmployeesAttendance($date = null, $status)
    {
        $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        $attendanceRecords = Attendance::with(['employee.user'])
            ->whereDate('date', $targetDate)
            ->where('status', $status)
            ->get();  
    
        $response = $attendanceRecords->map(function ($record) {
            $employee = $record->employee;
            $user = $employee ? $employee->user : null;
            return [
                'employee_id' => $record->employee_id,
                'name' => $user ? $user->name : null,
                'date' => $record->date,
                'status' => $record->status,
            ];
        });

        return Helpers::result("Attendance records retrieved successfully", Response::HTTP_OK, $response);
    }
}
