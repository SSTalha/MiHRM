<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\SalaryService;

class SalaryController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function getSalaryDetails($employeeId)
    {
        return $this->salaryService->getSalaryDetails($employeeId);
    }

    public function getAllSalaries()
    {
        return $this->salaryService->getAllSalaries();
    }
}
