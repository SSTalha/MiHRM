<?php

namespace App\DTOs;

use Carbon\Carbon;

class AttendanceDTO extends BaseDTOs
{
    public $employee_id;
    public $date;
    public $check_in_time;
    public $status;

    public function __construct($employee_id, $date = null, $check_in_time = null, $status = 'present')
    {
        $this->employee_id = $employee_id;
        $this->date = $date ?? Carbon::today();
        $this->check_in_time = $check_in_time ?? Carbon::now();
        $this->status = $status;
    }

}
