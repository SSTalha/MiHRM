<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

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

}
