<?php

namespace App\Services\Employee;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Attendance;

class WorkingHourService
{
    /**
     * Get total working hours for an employee based on the date and frequency.
     *
     * @param int $employeeId
     * @param string|null $date
     * @param string|null $frequency
     * @return array
     */
    public function calculateWorkingHours(int $employeeId, string $date = null, string $frequency = null)
    {
        
        $startDate = $this->getStartDate($date, $frequency);
        $endDate = $this->getEndDate($date, $frequency);

        
        if (!$startDate || !$endDate) {
            return ['error' => "Invalid date or frequency provided."];
        }

        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $dailyWorkingHours = [];
        $totalSeconds = 0;
        
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            $dailyWorkingHours[$dateKey] = [
                'date' => $dateKey,
                'working_hours' => $this->calculateDailyHours($attendances, $dateKey, $totalSeconds),
            ];

            $currentDate->addDay();
        }

        $formattedTotalHours = gmdate('H:i:s', $totalSeconds);

        
        $data=array(
            'employee_id' => $employeeId,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_working_hours' => $formattedTotalHours,
            'daily_working_hours' => array_values($dailyWorkingHours));
        return Helpers::result("Working hours retrieved successfully",200,$data);
    }
    // ######################## Private methods #################
    private function getStartDate(?string $date, ?string $frequency)
    {
        if ($frequency === 'weekly') {
            return Carbon::parse($date)->startOfWeek();
        } elseif ($frequency === 'monthly') {
            return Carbon::parse($date)->startOfMonth();
        } elseif ($date) {
            return Carbon::parse($date)->startOfDay();
        }
        return null;
    }

    private function getEndDate(?string $date, ?string $frequency)
    {
        if ($frequency === 'weekly') {
            return Carbon::parse($date)->endOfWeek();
        } elseif ($frequency === 'monthly') {
            return Carbon::parse($date)->endOfMonth();
        } elseif ($date) {
            return Carbon::parse($date)->endOfDay();
        }
        return null;
    }

    private function calculateDailyHours($attendances, string $dateKey, &$totalSeconds)
    {
        foreach ($attendances as $attendance) {
            if ($attendance->date === $dateKey && $attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->check_in_time);
                $checkOut = Carbon::parse($attendance->check_out_time);
                $dailyWorkingSeconds = max(0, $checkOut->diffInSeconds($checkIn));
                $totalSeconds += $dailyWorkingSeconds;
                return gmdate('H:i:s', $dailyWorkingSeconds);
            }
        }
        return '00:00:00'; // Return zero hours if no valid check-in/check-out
    }
}
