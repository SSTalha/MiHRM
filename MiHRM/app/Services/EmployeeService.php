<?php

namespace App\Services;
use App\DTOs\LeaveRequestDTO;

use App\Models\Employee;
use App\Models\LeaveRequest;
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

        return $leaveRequest;
    }
}