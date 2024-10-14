<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Employee\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function checkIn()
    {
        $employee = auth()->user()->employee; 

        return $this->attendanceService->checkIn($employee);

    }

    public function checkOut()
    {
        $employee = auth()->user()->employee; 

        return $this->attendanceService->checkOut($employee);

    }

        public function getEmployeesAttendence(Request $request)
    {
        $date = $request->input('date');
        $status = $request->input('status');
        return $this->attendanceService->getEmployeesAttendance($date, $status);

    }
}
