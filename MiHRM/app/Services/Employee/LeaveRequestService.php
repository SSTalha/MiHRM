<?php
namespace App\Services\Employee;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Spatie\Permission\Models\Role;

class LeaveRequestService
{
    /**
     * Get leave requests based on the user's role.
     *
     * @return array
     */
    public function getLeaveRequests()
    {
        try {

            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $leaveRequests = LeaveRequest::with('employee.user')->get();
            } elseif ($user->hasRole('hr')) {
                $employeeRole = Role::where('name', 'employee')->first();
 
                $leaveRequests = LeaveRequest::whereHas('employee.user.roles', function ($query) use ($employeeRole) {
                    $query->where('role_id', $employeeRole->id); // Only include employees
                })->with('employee.user')->get();
            } else {

                if (!$user->employee) {
                    return Helpers::result('No employee record found for the user.', 403);
                }
    
                $leaveRequests = LeaveRequest::where('employee_id', $user->employee->id)
                    ->with('employee.user')
                    ->get();
            }
    
            if ($leaveRequests->isEmpty()) {
                return Helpers::result('No leave requests found.', 200, []);
            }

            $leaveRequestsData = $leaveRequests->map(function ($leaveRequest) {
                return [
                    'id' => $leaveRequest->id,
                    'employee_id' => $leaveRequest->employee_id,
                    'employee_name' => $leaveRequest->employee->user->name ?? 'N/A', // Employee's user name
                    'start_date' => $leaveRequest->start_date,
                    'end_date' => $leaveRequest->end_date,
                    'reason' => $leaveRequest->reason,
                    'status' => $leaveRequest->status,
                ];
            });
    
            return Helpers::result('Leave requests retrieved successfully', 200, $leaveRequestsData);
    
        } catch (\Exception $e) {
            return Helpers::result('Failed to retrieve leave requests: ' . $e->getMessage(), 500);
        }
    }
}
