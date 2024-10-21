<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordRequest;
use App\Services\Employee\EmployeeService;
use App\Http\Requests\Employee\LeaveRequest;
use App\Http\Requests\Employee\UpdateProjectStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function passwordSetup(PasswordRequest $request){
        $data = $request->only(['email', 'token', 'password']);
        return $this->employeeService->passwordSetup($data);
    }

    public function submitLeaveRequest(LeaveRequest $request)
    {
        $data = $request->all();
        return $this->employeeService->submitLeaveRequest($data);
    }

    public function getAssignedProjects(Request $request)
    {
        return $this->employeeService->getAssignedProjects($request);
    }
    public function updateProjectStatus(UpdateProjectStatusRequest $request): JsonResponse
    {
        return $this->employeeService->updateProjectStatus($request);
    }

}
