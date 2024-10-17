<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Employee\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getEmployeesAttendence(Request $request)
    {
        $date = $request->input('date');
        $status = $request->input('status');
        return $this->attendanceService->getEmployeesAttendance($date, $status);

    }
    public function handleCheckInOut(Request $request): JsonResponse
    {
        return $this->attendanceService->handleCheckInOut($request);
    }
}
