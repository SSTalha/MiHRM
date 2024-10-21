<?php

namespace App\DTOs\LogsDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;

class ActivityLogDTO extends BaseDTOs
{
    public $request_log_id;
    public $url;
    public $method;
    public $ip_address;
    public $user_agent;
    public $activity;
    public $activity_status;
    public $status_code;

    /**
     * Summary of __construct
     * @param \Illuminate\Http\Request $request
     * @param int $requestLogId
     * @param bool $activityStatus
     * @param int $statusCode
     */
    public function __construct(Request $request, int $requestLogId, bool $activityStatus = false, int $statusCode) {
        $this->request_log_id = $requestLogId;
        $this->url = $request->fullUrl();
        $this->method = $request->method();
        $this->ip_address = $request->ip();
        $this->user_agent = $request->header('User-Agent');
        $this->activity = $request->route() ? $request->route()->getActionName() : null;
        $this->activity_status = $activityStatus;
        $this->status_code = $statusCode;
    }

}