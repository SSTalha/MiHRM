<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate a random date within the last month
        $date = Carbon::today()->subDays(rand(1, 30));

        // Generate random check-in and check-out times
        $checkInTime = Carbon::createFromTime(rand(8, 10), rand(0, 59), 0); // Between 8:00 AM and 10:59 AM
        $checkOutTime = (clone $checkInTime)->addHours(rand(7, 9))->addMinutes(rand(0, 59)); // 7-9 hours of work

        // Calculate working hours in seconds
        $workingSeconds = $checkOutTime->diffInSeconds($checkInTime);
        $workingHoursFormatted = gmdate('H:i:s', $workingSeconds); // Format as "H:i:s" string

        return [
            'employee_id' => rand(2,3),

            'date' => $date->format('Y-m-d'),
            'check_in_time' => $checkInTime->format('H:i:s'),
            'check_out_time' => $checkOutTime->format('H:i:s'),
            'status' => 'present', // Default to 'present'
            'working_hours' => $workingHoursFormatted, // Save working hours as a string in "H:i:s" format
        ];
    }
}
