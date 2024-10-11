<?php

namespace App\Services;
use App\Helpers\Helpers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\DTOs\LeaveRequestDTO;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
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

    public function getAssignedProjects()
    {
        
        $employeeId = Auth::user()->employee->id;
        $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
                                             ->with('project') 
                                             ->get();

        if ($assignedProjects->isEmpty()) {
            return Helpers::result("No projects assigned to this employee.", 404);
        }

        return Helpers::result("Assigned projects fetched successfully.", 200, [
            'assigned_projects' => $assignedProjects
        ]);
    }
}