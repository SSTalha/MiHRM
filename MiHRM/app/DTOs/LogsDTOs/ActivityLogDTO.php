<?php

namespace App\DTOs\LogsDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;

class ActivityLogDTO extends BaseDTOs
{
    public $user_id;
    public $url;
    public $method;
    public $ip_address;
    public $user_agent;
    public $action;
    public $status_code;

    /**
     * Summary of __construct
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @param int $statusCode
     */
    public function __construct(Request $request, int $userId, int $statusCode) {
        $this->user_id = $userId;
        $this->url = $request->fullUrl();
        $this->method = $request->method();
        $this->ip_address = $request->ip();
        $this->user_agent = $request->header('User-Agent');
        $this->action = $request->route() ? $request->route()->getActionName() : null;
        $this->status_code = $statusCode;
    }

}