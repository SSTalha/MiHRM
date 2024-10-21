<?php
namespace App\Services\Employee;

use App\Constants\Messages;
use App\Models\LeaveRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Spatie\Permission\Models\Role;

class LeaveRequestService
{
    /**
     * Summary of getLeaveRequests
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getLeaveRequests($request)
    {
        try {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $leaveRequests = LeaveRequest::with('employee.user')->get();
            } elseif ($user->hasRole('hr')) {
                $employeeRole = Role::where('name', 'employee')->first();
 
                $leaveRequests = LeaveRequest::whereHas('employee.user.roles', function ($query) use ($employeeRole) {
                    $query->where('role_id', $employeeRole->id);
                })->with('employee.user')->get();
            } else {

                if (!$user->employee) {
                    return Helpers::result('No employee record found for the user.', Response::HTTP_OK);
                }
    
                $leaveRequests = LeaveRequest::where('employee_id', $user->employee->id)
                    ->with('employee.user')
                    ->get();
            }
    
            if ($leaveRequests->isEmpty()) {
                return Helpers::result('No leave requests found.', Response::HTTP_OK);
            }

            $leaveRequestsData = $leaveRequests->map(function ($leaveRequest) {
                return [
                    'id' => $leaveRequest->id,
                    'employee_id' => $leaveRequest->employee_id,
                    'employee_name' => $leaveRequest->employee->user->name ?? 'N/A',
                    'start_date' => $leaveRequest->start_date,
                    'end_date' => $leaveRequest->end_date,
                    'reason' => $leaveRequest->reason,
                    'status' => $leaveRequest->status,
                ];
            });
    
            return Helpers::result('Leave requests retrieved successfully', 200, $leaveRequestsData);
    
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
