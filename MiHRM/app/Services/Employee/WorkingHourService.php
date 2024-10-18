<?php

namespace App\Services\Employee;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WorkingHourService
{
    /**
     * Get total working hours for an employee based on the date and frequency.
     *
     * @param int|null $employeeId
     * @param string|null $date
     * @param string|null $frequency
     * @return array
     */
    public function calculateWorkingHours(?int $employeeId = null, string $date = null, string $frequency = null)
{
    if (!$employeeId) {
        $employeeId = Auth::user()->employee_id ?? Auth::user()->employee->id;
    }

    // Find the employee by the employee_id
    $employee = Employee::find($employeeId);
    if (!$employee) {
    return ['error' => "Employee with ID {$employeeId} not found."];
        }


    // Get the employee's name from the associated user
    $employeeName = $employee->user->name;  // Assuming employee has a 'user' relation and 'name' is in 'user' table.

    $startDate = $this->getStartDate($date, $frequency);
    $endDate = $this->getEndDate($date, $frequency);

    if (!$startDate || !$endDate) {
        return ['error' => "Invalid date or frequency provided."];
    }

    $attendances = Attendance::where('employee_id', $employeeId)
        ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        ->get();

    $dailyWorkingHours = [];
    $totalSeconds = 0;
    
    $currentDate = $startDate->copy();
    while ($currentDate->lte($endDate)) {
        $dateKey = $currentDate->format('Y-m-d');
        $weekdayName = $currentDate->format('l'); 
        $attendanceRecord = $attendances->where('date', $dateKey)->first();
        $checkIn = $attendanceRecord->check_in_time ?? null;
        $checkOut = $attendanceRecord->check_out_time ?? null;

        $status = $this->getStatus($attendanceRecord);

        $dailyWorkingHours[$dateKey] = [
            'date' => $dateKey,
            'weekday' => $weekdayName,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'working_hours' => $this->calculateDailyHours($attendances, $dateKey, $totalSeconds),
            'status' => $status,
        ];

        $currentDate->addDay();
    }

    $formattedTotalHours = gmdate('H:i:s', $totalSeconds);

    $data = [
        'employee_id' => $employeeId,
        'employee_name' => $employeeName,  // Using the employee name corresponding to employee_id
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
        'total_working_hours' => $formattedTotalHours,
        'daily_working_hours' => array_values($dailyWorkingHours),
    ];

    return Helpers::result("Working hours retrieved successfully", Response::HTTP_OK, $data);
}


    // ######################## Private methods #################

    private function getStartDate(?string $date, ?string $frequency)
    {
        if ($frequency === 'weekly') {
            return Carbon::parse($date)->startOfWeek();
        } elseif ($frequency === 'monthly') {
            return Carbon::parse($date)->startOfMonth();
        } elseif ($date) {
            return Carbon::parse($date)->startOfDay();
        }
        return null;
    }

    private function getEndDate(?string $date, ?string $frequency)
    {
        if ($frequency === 'weekly') {
            return Carbon::parse($date)->endOfWeek();
        } elseif ($frequency === 'monthly') {
            return Carbon::parse($date)->endOfMonth();
        } elseif ($date) {
            return Carbon::parse($date)->endOfDay();
        }
        return null;
    }

    private function calculateDailyHours($attendances, string $dateKey, &$totalSeconds)
    {
        foreach ($attendances as $attendance) {
            if ($attendance->date === $dateKey && $attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->check_out_time);
                $dailyWorkingSeconds = max(0, $checkOut->diffInSeconds($checkIn));
                $totalSeconds += $dailyWorkingSeconds;
                return gmdate('H:i:s', $dailyWorkingSeconds);
            }
        }
        return '00:00:00';
    }

    /**
     * Get the status of the employee for the day (present/absent/onleave).
     */
    private function getStatus($attendanceRecord)
    {
        if (!$attendanceRecord) {
            return 'absent';
        }

        if ($attendanceRecord->is_on_leave) {
            return 'onleave';
        }

        return ($attendanceRecord->check_in_time && $attendanceRecord->check_out_time) ? 'present' : 'absent';
    }

    /**
     * Get all attendance records with employee_id and employee name.
     *
     * @return array
     */
    public function getAllAttendanceRecords()
{
    try {

        $employee = auth()->user();
        $employeeName = $employee->name;
        $attendances = Attendance::with('employee.user')->get();

        $attendanceData = [];
        foreach ($attendances as $attendance) {
            $attendanceData[] = [
                'employee_id' => $attendance->employee_id,
                'employee_name' => $attendance->employee->user->name, 
                'date' => $attendance->date,
                'check_in' => $attendance->check_in_time ?? 'N/A',
                'check_out' => $attendance->check_out_time ?? 'N/A',
                'status' => $this->getStatus($attendance), 
            ];
        }

        return Helpers::result("Attendance records retrieved successfully", Response::HTTP_OK, $attendanceData);

    } catch (\Exception $e) {
        return Helpers::result("There was an error fetching records: " . $e->getMessage(), Response::HTTP_BAD_REQUEST);
    }
}

}