<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\DTOs\EmployeeUpdateDTO;

class AdminService
{
    /**
     * Fetch employees by department ID along with user and department information
     *
     * @param int $department_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployeesByDepartment($department_id)
    {
        $employees = Employee::where('department_id', $department_id)
            ->with(['user:id,name,email', 'department:id,name'])
            ->get(['id', 'user_id', 'position', 'date_of_joining', 'department_id']);

        if ($employees->isEmpty()) {
            return Helpers::result("No employees found for this department", 404);
        }

        $formattedData = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->user->name,
                'email' => $employee->user->email,
                'position' => $employee->position,
                'date_of_joining' => $employee->date_of_joining,
                'department_name' => $employee->department->name
            ];
        });

        return Helpers::result("Employees fetched successfully", 200, [
            'department_id' => $department_id,
            'employees' => $formattedData
        ]);
    }

    /**
     * Delete a user and their related employee record
     *
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserAndEmployee($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return Helpers::result("User not found", 404);
        }

        Employee::where('user_id', $user_id)->delete();

        $user->delete();

        return Helpers::result("User and employee deleted successfully", 200);
    }

    public function updateEmployee($data, $employee_id)
    {
        // Find the employee by ID
        $employee = Employee::find($employee_id);

        if (!$employee) {
            // If the employee is not found, return a 404 response
            return Helpers::result("Employee not found", 404);
        }

        // Create the DTO for the update
        $dto = new EmployeeUpdateDTO($data);

        // Update the employee record using the DTO data
        $employee->update($dto->toArray());

        // Return a success response
        return Helpers::result("Employee updated successfully", 200, [
            'employee' => $employee
        ]);
    }
}