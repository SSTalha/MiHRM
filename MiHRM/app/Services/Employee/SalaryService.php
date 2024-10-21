<?php

namespace App\Services\Employee;

use App\Constants\Messages;
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
        if (Auth::user()->hasRole('admin')) {
            $employeeId = $request->input('employee_id');
            $month = $request->input('month') ?? Carbon::now()->format('Y-m');
        } else {

            $employeeId = Auth::user()->employee_id ?? Auth::user()->employee->id;
            $month = $request->input('month') ?? Carbon::now()->format('Y-m');
        }

        if (!$month) {
            return Helpers::result('Month is required', Response::HTTP_BAD_REQUEST);
        }

        $employee = Employee::findOrFail($employeeId);

        $monthStart = Carbon::parse($month)->startOfMonth();
        $monthEnd = Carbon::parse($month)->endOfMonth();

        $salary = Salary::where('employee_id', $employeeId)
                        ->whereMonth('paid_date', $monthStart->format('m'))
                        ->whereYear('paid_date', $monthStart->format('Y'))
                        ->first();

        if (!$salary) {
            return Helpers::result('No salary record found for the specified employee and month', Response::HTTP_NOT_FOUND);
        }

        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
            ->get();

        $totalWorkingDays = 0;
        $totalSecondsWorked = 0;

        foreach ($attendances as $attendance) {
            if ($attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->check_out_time);
                $dailySecondsWorked = max(0, $checkOut->diffInSeconds($checkIn));

                $totalSecondsWorked += $dailySecondsWorked;
                $totalWorkingDays++;
            }
        }

        $totalWorkingHours = gmdate('H:i:s', $totalSecondsWorked);

        $department = $employee->department;


        $responseData = [
            'employee_id' => $salary->employee_id,
            'employee_name' => $employee->user->name, 
            'salary' => $employee->pay,
            'status' => $salary->status,
            'paid_date' => $salary->paid_date,
            'salary_month' => $monthStart->format('F Y'), 
            'employee_position' => $employee->position,
            'employee_department' => $department ? $department->name : null,
            'total_working_days' => $totalWorkingDays,
            'total_working_hours' => $totalWorkingHours
        ];

        return Helpers::result('Salary details retrieved successfully', Response::HTTP_OK, $responseData);
    } catch (\Throwable $e) {
        return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}