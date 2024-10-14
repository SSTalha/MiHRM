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
            // Get the authenticated user's role
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                // Admin can get all leave requests but not their own
                $leaveRequests = LeaveRequest::with('user')
                    ->where('employee_id', '!=', $user->employee_id)
                    ->get();
            } elseif ($user->hasRole('HR')) {
                // HR can only get leave requests from employees (not HRs)
                $employeeRole = Role::where('name', 'employee')->first();
                $leaveRequests = LeaveRequest::whereHas('user.roles', function ($query) use ($employeeRole) {
                    $query->where('role_id', $employeeRole->id);
                })->with('user')
                  ->get();
            } else {
                // Other employees can only get their own leave requests
                $leaveRequests = LeaveRequest::where('employee_id', $user->employee_id)
                    ->with('user')
                    ->get();
            }

            if ($leaveRequests->isEmpty()) {
                return Helpers::result('No leave requests found.', 200, []);
            }

            // Prepare the response data
            $leaveRequestsData = $leaveRequests->map(function ($leaveRequest) {
                return [
                    'id' => $leaveRequest->id,
                    'employee_id' => $leaveRequest->employee_id,
                    'employee_name' => $leaveRequest->user->name, // Assuming 'name' is in the users table
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
