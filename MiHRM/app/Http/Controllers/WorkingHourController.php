<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Employee\WorkingHourService;
use App\Http\Requests\Employee\WorkingHourRequest;

class WorkingHourController extends Controller
{
    protected $workingHourService;

    public function __construct(WorkingHourService $workingHourService)
    {
        $this->workingHourService = $workingHourService;
    }

    /**
     * Get the total working hours for an employee on a weekly or monthly basis.
     *
     * @param WorkingHourRequest $request
     * @return JsonResponse
     */
    public function getWorkingHours(WorkingHourRequest $request)
    {
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $frequency = $request->input('frequency');

        // Call the service to calculate working hours
        return $this->workingHourService->calculateWorkingHours($employeeId, $date, $frequency);

       
    }
}
