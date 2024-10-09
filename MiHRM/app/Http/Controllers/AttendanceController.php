<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    // ######## Check-in ########
    public function checkIn()
    {
        $employee = auth()->user()->employee; 

        return $this->attendanceService->checkIn($employee);

    }

        // ######## Check-out ########

    public function checkOut()
    {
        $employee = auth()->user()->employee; 

        return $this->attendanceService->checkOut($employee);

    }

        // ######## Get Attendance Record ########
        public function getEmployeesAttendence(Request $request)
    {
        $date = $request->input('date');
        $status = $request->input('status');
        return $this->attendanceService->getEmployeesAttendence($date, $status);

    }
}
