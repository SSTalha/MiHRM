<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;

class SalaryResponseDTO extends BaseDTOs
{
    public $salary;
    public $status;
    public $paid_date;

    public function __construct($salary)
    {
        $this->salary = $salary->pay;
        $this->status = $salary->status;
        $this->paid_date = $salary->paid_date;
    }
}