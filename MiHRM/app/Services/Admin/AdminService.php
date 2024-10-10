<?php

namespace App\Services\Admin;

use App\Helpers\Helpers;

use App\Models\User;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\ProjectAssignment;

use App\DTOs\ProjectDTOs\ProjectCreateDTO;
use App\DTOs\EmployeeDTOs\EmployeeUpdateDTO;
use App\DTOs\ProjectDTOs\ProjectAssignmentDTO;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    /**
     * Fetch employees by department ID
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

    /**
     * Summary of updateEmployee
     * @param mixed $data
     * @param mixed $employee_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updateEmployee($data, $employee_id)
    {
        $employee = Employee::find($employee_id);

        if (!$employee) {
            return Helpers::result("Employee not found", 404);
        }

        $dto = new EmployeeUpdateDTO($data);

        $employee->update($dto->toArray());

        return Helpers::result("Employee updated successfully", 200, [
            'employee' => $employee
        ]);
    }

    /**
     * Summary of getAllDepartments
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllDepartments(){
        $departments = Department::all();
        return Helpers::result('Departments retrieved successfully', 200, $departments);
    }

    /**
     * Summary of handleLeaveRequest
     * @param mixed $leaveRequestId
     * @param mixed $status
     * @return void
     */
    public function handleLeaveRequest($leaveRequestId, $status)
    {
        $currentUser = Auth::user();
        $leaveRequest = LeaveRequest::find($leaveRequestId);

        if (!$leaveRequest) {
            Helpers::result('Leave request not found', 404);
        }

        $requestingEmployee = Employee::find($leaveRequest->employee_id);

        if (!$requestingEmployee) {
            Helpers::result('Employee record not found', 404);
        }

        $requestingUser = $requestingEmployee->user;

        if ($currentUser->id === $requestingUser->id) {
            Helpers::result('You cannot approve/reject your own leave request.', 403);
        }

        if ($requestingUser->hasRole('hr')) {
            if (!$currentUser->hasRole('admin')) {
                Helpers::result('Only Admin can approve/reject leave requests from HR.', 403);
            }
        } elseif ($requestingUser->hasRole('employee')) {
            if (!$currentUser->hasRole('admin') && !$currentUser->hasRole('hr')) {
                Helpers::result('Only Admin or HR can approve/reject leave requests from Employees.', 403);
            }
        }
        $leaveRequest->update(['status' => $status]);
        if ($status === 'approved') {
            $this->updateAttendanceStatus($leaveRequest->employee_id, 'onleave');
            Helpers::result('Leave request has been approved', 200);
        }

    }

    /**
     * Summary of updateAttendanceStatus
     * @param mixed $employeeId
     * @param mixed $status
     * @return void
     */
    protected function updateAttendanceStatus($employeeId, $status)
    {
        $today = now()->toDateString();
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('created_at', $today)
            ->first();

        if ($attendance) {
            $attendance->update(['status' => $status]);
        }
    }

    /**
     * Summary of createProject
     * @param mixed $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createProject($data)
    {
        
        $dto = new ProjectCreateDTO($data);

        $project = Project::create($dto->toArray());

        return Helpers::result("Project created successfully.", 201, $project);
    }

    /**
     * Summary of assignProject
     * @param mixed $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function assignProject($data){
        
        $dto = new ProjectAssignmentDTO($data);
        $employee = Employee::find($dto->employee_id);

        if ($employee->user->hasRole('hr')) {
            return Helpers::result("Can't assign project. The user is an HR.", 400);
        }

        $assignment = ProjectAssignment::create($dto->toArray());

        return Helpers::result("Project assigned successfully.", 201, $assignment);
    }

    /**
     * Summary of getAllAssignedProjects
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllAssignedProjects(){
        $assignedProjects = ProjectAssignment::with(['project', 'employee.user']) ->get();

        if ($assignedProjects->isEmpty()) {
            return Helpers::result("No projects assigned yet.", 404);
        }

        return Helpers::result("All assigned projects fetched successfully.", 200, $assignedProjects);
    }
}