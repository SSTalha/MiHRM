<?php

namespace App\Http\Middleware;

use App\DTOs\LogsDTOs\RequestLogDTO;
use App\Models\RequestLogs;
use Closure;
use Illuminate\Http\Request;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $requestLogDTO = new RequestLogDTO($request);
        $requestLog = RequestLogs::create($requestLogDTO->toArray());
        $request['request_log_id'] = $requestLog->id;

        // dd($requestLog);
       return $next($request);
    }

    public function terminate($request , $response){
        RequestLogs::find($request['request_log_id'])->update([
            'response_body' => json_encode($response->getContent()),
            'response_status_code' => $response->getStatusCode(),
        ]);
    }
}
