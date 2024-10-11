<?php

namespace App\Http\Controllers;

use App\Services\Employee\SalaryService;
use Illuminate\Http\Request;

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
