<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\LeaveRequest;
use App\Services\Employee\EmployeeService;

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

}
