<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Response;
use App\Helpers\Helpers;

class SalaryService
{
    public function getSalaryDetails($employeeId)
    {
        try {
            // Fetch the employee record for the given employee ID
            $employee = Employee::findOrFail($employeeId);
            
            // Fetch the latest salary record for the employee
            $salary = Salary::where('employee_id', $employeeId)->latest()->first();

            // Prepare the response data
            $responseData = [
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
    // ######### Get All Salary #############
    public function getAllSalaries()
    {
        try {
            // Fetch all salary records
            $salaries = Salary::with('employee')->get();

            // Prepare the response data
            $salaryData = $salaries->map(function ($salary) {
                return [
                    'employee_id' => $salary->employee_id,
                    'employee_name' => $salary->employee->name, // Assuming 'name' is a column in the Employee model
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
