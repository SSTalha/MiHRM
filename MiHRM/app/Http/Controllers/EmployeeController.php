<?php

namespace App\Http\Controllers;

use App\Services\Employee\EmployeeService;
use App\Http\Requests\Employee\LeaveRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use Illuminate\Http\JsonResponse;


class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function submitLeaveRequest(LeaveRequest $request)
    {
        $data = $request->all();
        $leaveRequest = $this->employeeService->submitLeaveRequest($data);

        return response()->json(['message' => 'Leave request submitted successfully.', 'data' => $leaveRequest], 201);
    }

    public function getAssignedProjects()
    {
        return $this->employeeService->getAssignedProjects();
    }
    public function updateProjectStatus(UpdateProjectStatusRequest $request): JsonResponse
    {
       
        return $this->employeeService->updateProjectStatus($request->validated());
    }

}
