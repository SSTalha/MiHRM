<?php

namespace App\Services\Employee;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\DTOs\EmployeeDTOs\AttendanceDTO;
use Symfony\Component\HttpFoundation\Response;

class AttendanceService
{
    public function handleCheckInOut(Request $request)
    {
        try {
            $employee = Auth::user()->employee;
            $type = $request->input('type');

            if (!$employee) {
                return Helpers::result("Employee not found for the authenticated user.", Response::HTTP_NOT_FOUND);
            }

            if ($type === 'check_in') {
                return $this->checkIn($employee); 
            } elseif ($type === 'check_out') {
                return $this->checkOut($employee); 
            } else {
                return Helpers::result("Invalid action type provided.", Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return Helpers::result("An error occurred: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * /check in
     * @param \App\Models\Employee $employee
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function checkIn(Employee $employee)
    {
        try{
            $today = Carbon::today();
            $attendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', $today)
                                    ->first();
            if ($attendance) {
                $attendanceDTO = new AttendanceDTO($employee->id, $today, Carbon::now(), 'present');
                $attendance->update($attendanceDTO->toArray());
            
            } else {
                $attendanceDTO = new AttendanceDTO($employee->id);
                Attendance::create($attendanceDTO->toArray());
            }
            return Helpers::result("Check-in recorded successfully", Response::HTTP_OK);
        
        }catch(\Exception $e){
            return Helpers::result("Error checking in: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of checkOut
     * @param \App\Models\Employee $employee
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function checkOut(Employee $employee)
    {
        try{
            $today = Carbon::today();
            $attendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', $today)
                                    ->first();  // Changed get() to first() for single record

            if ($attendance) {  
                $checkOutTime = Carbon::now();
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $workingSeconds = $checkInTime->diffInSeconds($checkOutTime);

                $hours = floor($workingSeconds / 3600);
                $minutes = floor(($workingSeconds % 3600) / 60);
                $seconds = $workingSeconds % 60;

                $workingHoursFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                
                $attendance->update([
                    'check_out_time' => $checkOutTime,
                    'working_hours' => $workingHoursFormatted, 
                ]);

            }
            $workingHour=[
                'working hours'=>$attendance->working_hours
            ];
            return Helpers::result("Check-out recorded successfully", Response::HTTP_OK, $workingHour);
        }catch(\Exception $e){
            return Helpers::result("Error checking out: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        
        }
    }

     
    /**
     * getEmployeesAttendance
     *
     * @param  mixed $date
     * @param  mixed $status
     * @param  mixed $request
     * @return void
     */
    public function getEmployeesAttendance($date = null, $status = null)
    {
        try{
            $user = auth()->user();
            $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

            if($user->hasRole('employee')){
                $employeeId = $user->employee->id;

                $attendanceRecords = Attendance::with(['employee.user'])
                ->where('employee_id', $employeeId)
                ->when($targetDate, function ($query, $targetDate) {
                    return $query->whereDate('date', $targetDate);
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->get();

            }else{
                $attendanceRecords = Attendance::with(['employee.user'])
                ->when($targetDate, function ($query, $targetDate) {
                    return $query->whereDate('date', $targetDate);
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->get();
            }

            $response = $attendanceRecords->map(function ($record) {
                $employee = $record->employee;
                $user = $employee ? $employee->user : null;
                return [
                    'employee_id' => $record->employee_id,
                    'name' => $user ? $user->name : null,
                    'date' => $record->date,
                    'status' => $record->status,
                ];
            });
            return Helpers::result("Attendance records retrieved successfully", Response::HTTP_OK, $response);
        
        }catch(\Exception $e){
            return Helpers::result("Error getting employee attendance: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    
    /**
     * getAttendanceCount
     *
     * @return void
     */
public function getAttendanceCount()
{
    $userID = Auth::user()->id;

    $employee = Employee::where('user_id', $userID)->first();

    if (!$employee) {
        return Helpers::result('Employee record not found', Response::HTTP_NOT_FOUND);
    }

    $employeeId = $employee->id; 
    $employeeName = Auth::user()->name;

    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::today();
    $attendance = Attendance::where('employee_id', $employeeId) 
        ->whereBetween('date', [$startDate, $endDate])
        ->get();

    if ($attendance->isEmpty()) {
        return Helpers::result('No attendance records found', Response::HTTP_OK);
    }

    $presentCount = $attendance->where('status', 'present')->count();
    $absentCount = $attendance->where('status', 'absent')->count();
    $onleaveCount = $attendance->where('status', 'onleave')->count();

    return Helpers::result('Attendance counts retrieved successfully', Response::HTTP_OK, [
        'employee_name' => $employeeName,
        'present' => $presentCount,
        'absent' => $absentCount,
        'onleave' => $onleaveCount
    ]);
}
    
    /**
     * getAttendanceByDate
     *
     * @param  mixed $date
     * @return void
     */
    public function getAttendanceByDate($request)
    {
    try {
        $targetDate = $request['date'] ? $request['date'] : Carbon::today()->toDateString();
        $attendanceRecords = Attendance::whereDate('date', $targetDate)->get();
        
        $totalPresent = $attendanceRecords->where('status', 'present')->count();
        $totalAbsent = $attendanceRecords->where('status', 'absent')->count();
        $totalOnLeave = $attendanceRecords->where('status', 'onleave')->count();

        $employeeRecord = $attendanceRecords->map(function ($record) {
            $employee = $record->employee;
            $user = $employee ? $employee->user : null;

            return [
                'employee_id' => $record->employee_id,
                'name' => $user ? $user->name : null,
                'status' => $record->status,
            ];
        });

        return Helpers::result("Attendance records retrieved successfully", Response::HTTP_OK, [
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'totalOnLeave' => $totalOnLeave,
            'employee_record' => $employeeRecord,
        ]);
    } catch (\Exception $e) {
        return Helpers::result("Error getting attendance records: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

}
