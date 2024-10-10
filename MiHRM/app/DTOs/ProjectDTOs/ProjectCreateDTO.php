<?php

namespace App\DTOs\ProjectDTOs;

use App\DTOs\BaseDTOs;
class ProjectCreateDTO extends BaseDTOs
{
    public string $title;
    public string $description;
    public string $deadline;

    /**
     * Construct the DTO with the input request.
     */
    public function __construct(mixed $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->deadline = $data['deadline'];
    }
}
