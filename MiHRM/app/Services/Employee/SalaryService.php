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
public function getSalaryDetails($request)
{
    try {
        // Check if the user is admin, and allow employee_id and month input
        if (Auth::user()->hasRole('admin')) {
            $employeeId = $request->input('employee_id');
            $month = $request->input('month') ?? Carbon::now()->format('Y-m'); // Use current month if not provided
        } else {
            // For HR and employees, use the logged-in user's employee ID
            $employeeId = Auth::user()->employee_id ?? Auth::user()->employee->id;
            $month = $request->input('month') ?? Carbon::now()->format('Y-m'); // Allow month input, default to current month
        }

        // Validate that month is provided
        if (!$month) {
            return Helpers::result('Month is required', Response::HTTP_BAD_REQUEST);
        }

        // Find the employee
        $employee = Employee::findOrFail($employeeId);

        // Parse the month and generate start and end dates for the month
        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();

        // Get the salary record for the provided employee and month
        $salary = Salary::where('employee_id', $employeeId)
                        ->whereMonth('paid_date', $monthStart->format('m'))
                        ->whereYear('paid_date', $monthStart->format('Y'))
                        ->first();

        if (!$salary) {
            return Helpers::result('No salary record found for the specified employee and month', Response::HTTP_NOT_FOUND);
        }

        // Get attendance records for the specified month
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
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
            'employee_name' => $employee->user->name, // Assuming employee's name is stored in the user table
            'salary' => $employee->pay,
            'status' => $salary->status,
            'paid_date' => $salary->paid_date,
            'salary_month' => $monthStart->format('F Y'), // Format the month of the salary
            'employee_position' => $employee->position,
            'employee_department' => $department ? $department->name : null,
            'total_working_days' => $totalWorkingDays,
            'total_working_hours' => $totalWorkingHours
        ];

        return Helpers::result('Salary details retrieved successfully', Response::HTTP_OK, $responseData);
    } catch (\Exception $e) {
        return Helpers::result('Failed to retrieve salary details: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}