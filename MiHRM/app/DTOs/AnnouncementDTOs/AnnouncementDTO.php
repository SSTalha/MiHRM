<?php

namespace App\DTOs\AnnouncementDTOs;

use App\DTOs\BaseDTOs;
use Carbon\Carbon;

class AnnouncementDTO extends BaseDTOS
{
    public $user_id;
    public $title;
    public $text;
    public $published_at;
    public $is_published;

    public function __construct(mixed $request, int $user_id)
    {
        $this->user_id = $user_id;
        $this->title = $request['title'];
        $this->text = $request['text'];
        $this->published_at = $request['published_at'] ?? Carbon::now();
        $this->is_published = Carbon::parse($this->published_at)->isPast();
    }
}
