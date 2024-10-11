<?php

namespace App\DTOs\ProjectDTOs;

use App\DTOs\BaseDTOs;

class ProjectAssignmentDTO extends BaseDTOs
{
    public int $project_id;
    public int $employee_id;
    public string $status;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $data)
    {
        $this->project_id = $data['project_id'];
        $this->employee_id = $data['employee_id'];
        // $this->status = $data['status'];
    }
}
