<?php

namespace App\DTOs;

class EmployeeUpdateDTO extends BaseDTOs
{
    public int $department_id;
    public string $position;

    /**
     * Construct the EmployeeUpdateDTO with the input request.
     *
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        $this->department_id = $data['department_id']; // The department ID from the request
        $this->position = $data['position'];           // The position field from the request
    }
}
