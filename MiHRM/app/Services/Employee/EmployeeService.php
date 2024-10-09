<?php

namespace App\Services\Employee;
use App\Helpers\Helpers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\DTOs\EmployeeDTOs\LeaveRequestDTO;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
    /**
     * submitLeaveRequest
     * @param mixed $request
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function submitLeaveRequest($request){
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            throw new \Exception('Employee record not found for this user.');
        }

        $leaveRequestDTO = new LeaveRequestDTO($request, $employee->id);
        $leaveRequest = LeaveRequest::create($leaveRequestDTO->toArray());

        return Helpers::result('Leave request submitted',200,$leaveRequest);
    }

    /**
     * getAssignedProjects
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAssignedProjects()
    {
        
        $employeeId = Auth::user()->employee->id;
        $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
                                             ->with('project') 
                                             ->get();

        if ($assignedProjects->isEmpty()) {
            return Helpers::result("No projects assigned to this employee.", 404);
        }

        return Helpers::result("Assigned projects fetched successfully.", 200, $assignedProjects);
    }
}