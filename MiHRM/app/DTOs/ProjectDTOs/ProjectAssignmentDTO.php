<?php

namespace App\DTOs\ProjectDTOs;

use App\DTOs\BaseDTOs;

class ProjectAssignmentDTO extends BaseDTOs
{
    public int $project_id;
    public mixed $employee_ids;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $data)
    {
        $this->project_id = $data['project_id'];
        $this->employee_ids = is_array($data['employee_ids']) ? $data['employee_ids'] : [$data['employee_ids']];
    }
}
