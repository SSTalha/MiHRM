<?php

namespace App\DTOs\LogsDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;

class ActivityLogDTO extends BaseDTOs
{
    public $request_log_id;
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
        $this->activity = $this->getActivityDescription($request);
        $this->activity_status = $activityStatus;
        $this->status_code = $statusCode;
    }

    private function getActivityDescription(Request $request): ?string
    {
        $actionName = $request->route() ? $request->route()->getActionName() : null;
        if ($actionName) {
            [$controller, $method] = explode('@', $actionName);
            return "User has done $method";
        }
        return null;
    }


}