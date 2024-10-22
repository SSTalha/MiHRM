<?php

namespace App\DTOs\PerkDTOs;

use App\DTOs\BaseDTOs;

class RequestPerkDTO extends BaseDTOs
{
    public int $employee_id;
    public array $requested_perks;
    public int $total_allowance;
   
    /**
     * __construct
     *
     * @param  mixed $data
     * @param  mixed $employeeId
     * @param  mixed $totalAllowance
     * @return void
     */
    public function __construct(mixed $requested_perks, int $employeeId, int $totalAllowance)
    {
        $this->employee_id = $employeeId;
        $this->requested_perks = $requested_perks;
        $this->total_allowance = $totalAllowance;
    }
}