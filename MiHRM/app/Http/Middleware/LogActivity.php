<?php

namespace App\Http\Middleware;

use App\DTOs\LogsDTOs\ActivityLogDTO;
use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivity
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
        $response = $next($request);

        if(Auth::check()){
            $user = Auth::user();

            $activityLogDto = new ActivityLogDTO($request, $user->id, $response->getStatusCode());
            
            ActivityLog::create($activityLogDto->toArray());
        }
        return $response;
    }
}
