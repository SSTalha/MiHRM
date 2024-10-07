<?php

namespace App\DTOs;

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
        $this->user_id = $userId;  // The newly created user's ID
        $this->position = $data['position']; // The position field from the request
        $this->department_id = $data['department_id']; // The department ID from the request
        $this->date_of_joining = now(); // Date of joining from the request
    }

    }
