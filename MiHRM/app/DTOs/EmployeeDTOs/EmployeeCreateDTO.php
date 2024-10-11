<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;
class EmployeeCreateDTO extends BaseDTOs
{
    public int $user_id;
    public string $position;
    public int $department_id;
    public string $date_of_joining;

    /**
     * Construct the EmployeeDTO with the input request.
     */
    public function __construct(mixed $data, int $userId)
    {
        $this->user_id = $userId;
        $this->position = $data['position']; 
        $this->department_id = $data['department_id'];
        $this->date_of_joining = now(); // Date of joining from the request
    }

    }
