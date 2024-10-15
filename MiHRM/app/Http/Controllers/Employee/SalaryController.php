<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\SalaryService;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function getSalaryDetails(Request $request)
    {
        return $this->salaryService->getSalaryDetails($request);
    }

    public function getAllSalaries()
    {
        return $this->salaryService->getAllSalaries();
    }
}
