<?php

namespace App\DTOs;

class LeaveRequestDTO extends BaseDTOs
{
    public string $start_date;
    public string $end_date;
    public ?string $reason;
    public int $employee_id;
    public string $status;

    public function __construct(mixed $data,int $employee_id) {
        $this->employee_id = $employee_id;
        $this->start_date = $data['start_date'];
        $this->end_date = $data['end_date'];
        $this->reason = $data['reason'];
        $this->status = 'pending';
    }

}