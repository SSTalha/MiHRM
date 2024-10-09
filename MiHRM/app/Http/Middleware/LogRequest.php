<?php

namespace App\Http\Middleware;

use App\Models\RequestLogs;
use Illuminate\Support\Facades\Auth;
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
        $requestLog = RequestLogs::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'request_body' => json_encode($request->all()),
        ]);
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
