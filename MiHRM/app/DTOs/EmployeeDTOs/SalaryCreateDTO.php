<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;
class SalaryCreateDTO extends BaseDTOs
{
    public int $employee_id;
    public int $pay;
    public string $status;
    public ?string $paid_date;

    
    public function __construct(mixed $request, int $employeeId)
    {
        $this->employee_id = $employeeId;
        $this->pay = isset($request['pay']) ? (int) $request['pay'] : 0;
        $this->status = 'unpaid';
        $this->paid_date = null;
    }

}