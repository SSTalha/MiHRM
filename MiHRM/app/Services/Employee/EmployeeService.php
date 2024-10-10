<?php

namespace App\Services\Employee;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\DTOs\EmployeeDTOs\LeaveRequestDTO;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeService
{
    /**
     * submitLeaveRequest
     * @param mixed $request
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function submitLeaveRequest($request)
    {
        try{
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();

            if (!$employee) {
                return Helpers::result('Employee record not found for this user.', Response::HTTP_NOT_FOUND);
            }

            $leaveRequestDTO = new LeaveRequestDTO($request, $employee->id);
            $leaveRequest = LeaveRequest::create($leaveRequestDTO->toArray());

            return Helpers::result('Leave request submitted', Response::HTTP_OK, $leaveRequest);

        }catch(\Exception $e){
            return Helpers::result("Error submitting leave request: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * getAssignedProjects
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAssignedProjects()
    {
        try{
            $employeeId = Auth::user()->employee->id;
            $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
                                                ->with('project')
                                                ->get();
            if ($assignedProjects->isEmpty()) {
                return Helpers::result("No projects assigned to this employee.", Response::HTTP_NOT_FOUND);
            }
            return Helpers::result("Assigned projects fetched successfully.", Response::HTTP_OK, $assignedProjects);
        
        }catch(\Exception $e){
            return Helpers::result("Error getting assigned projects: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
