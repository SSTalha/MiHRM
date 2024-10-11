<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Response;
use App\Helpers\Helpers;

class SalaryService
{
    /**
     * Summary of getSalaryDetails
     * @param mixed $employeeId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getSalaryDetails($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            
            $salary = Salary::where('employee_id', $employeeId)->latest()->first();

            $responseData = [
                'employee_id' => $salary->employee_id,
                'salary' => $employee->pay,              
                'status' => $salary ? $salary->status : 'unpaid', 
                'paid_date' => $salary->paid_date ?? null, 
                'employee_position' => $employee->position,
                'employee_department' => $employee->department_id 
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
