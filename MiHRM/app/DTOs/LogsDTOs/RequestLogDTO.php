<?php

namespace App\DTOs\LogsDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;

class RequestLogDTO extends BaseDTOs
{
    public $user_id;
    public $ip_address;
    public $user_agent;
    public $request_method;
    public $request_url;
    public $request_body;

    /**
     * Summary of __construct
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request) {
        $this->user_id = auth()->check() ? auth()->id() : null;
        $this->ip_address = $request->ip();
        $this->user_agent = $request->header('User-Agent');
        $this->request_method = $request->method();
        $this->request_url = $request->fullUrl();
        $this->request_body = json_encode($request->all());
    }

}