<?php

namespace App\Services\Employee;

use Carbon\Carbon;
use App\Models\Salary;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SalaryService
{
    /**
     * Summary of getSalaryDetails
     * @param mixed $employeeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
public function getSalaryDetails()
{
    try {
        // Retrieve the employee ID from the logged-in user
        $employeeId = Auth::user()->employee_id ?? Auth::user()->employee->id;

        // Find the employee
        $employee = Employee::findOrFail($employeeId);
        
        // Get the latest salary record
        $salary = Salary::where('employee_id', $employeeId)->latest()->first();
        
        // Get attendance records for the current month
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$currentMonthStart->format('Y-m-d'), $currentMonthEnd->format('Y-m-d')])
            ->get();

        // Initialize counters
        $totalWorkingDays = 0;
        $totalSecondsWorked = 0;

        // Loop through attendance records to calculate working days and hours
        foreach ($attendances as $attendance) {
            if ($attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->check_out_time);
                $dailySecondsWorked = max(0, $checkOut->diffInSeconds($checkIn));

                $totalSecondsWorked += $dailySecondsWorked;
                $totalWorkingDays++;  // Increment working days when employee is present
            }
        }

        // Convert total seconds worked to hours format
        $totalWorkingHours = gmdate('H:i:s', $totalSecondsWorked);

        // Get department name
        $department = $employee->department; // Assuming you have a relationship defined

        // Prepare the response data
        $responseData = [
            'employee_id' => $salary->employee_id,
            'salary' => $employee->pay,              
            'status' => $salary ? $salary->status : 'unpaid', 
            'paid_date' => $salary->paid_date ?? null, 
            'employee_position' => $employee->position,
            'employee_department' => $department ? $department->name : null, // Show department name instead of ID
            'total_working_days' => $totalWorkingDays,          // Total working days in the month
            'total_working_hours' => $totalWorkingHours         // Total working hours in the month
        ];

        return Helpers::result('Salary details retrieved successfully', Response::HTTP_OK, $responseData);
    } catch (\Exception $e) {
        return Helpers::result('Failed to retrieve salary details: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}



    /**
     * Summary of getAllSalaries
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllSalaries()
    {
        try {
            $salaries = Salary::with('employee')->get();

            $salaryData = $salaries->map(function ($salary) {
                return [
                    'employee_id' => $salary->employee_id,
                    'employee_name' => $salary->employee->name,
                    'salary' => $salary->pay,
                    'status' => $salary->status,
                    'paid_date' => $salary->paid_date,
                    'created_at' => $salary->created_at,
                ];
            });

            return Helpers::result('All salaries retrieved successfully', Response::HTTP_OK, $salaryData);
        } catch (\Exception $e) {
            return Helpers::result('Failed to retrieve salaries: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, []);
        }
    }
}
