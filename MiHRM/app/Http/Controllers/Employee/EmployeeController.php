<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\EmployeeService;
use App\Http\Requests\Employee\LeaveRequest;
use App\Http\Requests\Employee\UpdateProjectStatusRequest;
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
        return $this->employeeService->submitLeaveRequest($data);
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
