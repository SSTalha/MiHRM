<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\Helpers\Helpers;

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
        // Retrieve employees by department_id and eager load related user and department
        $employees = Employee::where('department_id', $department_id)
            ->with(['user:id,name,email', 'department:id,name']) // Load specific columns from related tables
            ->get(['id', 'user_id', 'position', 'date_of_joining', 'department_id']); // Select employee-specific columns

        // If no employees found
        if ($employees->isEmpty()) {
            return Helpers::result("No employees found for this department", 404);
        }

        // Format the data and return success response
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
        // Find the user
        $user = User::find($user_id);

        if (!$user) {
            // User does not exist, return error response
            return Helpers::result("User not found", 404);
        }

        // Delete the related employee record
        Employee::where('user_id', $user_id)->delete();

        // Delete the user
        $user->delete();

        // Return a success response
        return Helpers::result("User and employee deleted successfully", 200);
    }
}
