<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Employee\LeaveRequestService;

class LeaveRequestController extends Controller
{
     protected $leaveRequestService;

    public function __construct(LeaveRequestService $leaveRequestService)
    {
        $this->leaveRequestService = $leaveRequestService;
    }

    public function getLeaveRequest()
    {
        return $this->leaveRequestService->getLeaveRequests();
    }
}
