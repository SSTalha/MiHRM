<?php

namespace App\Http\Middleware;

use App\DTOs\LogsDTOs\ActivityLogDTO;
use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    protected $activityPaths = [
        'App\Http\Controllers\Employee\EmployeeController@submitLeaveRequest',
        'App\Http\Controllers\Employee\EmployeeController@updateProjectStatus',
        'App\Http\Controllers\Employee\AttendanceController@handleCheckInOut',
        'App\Http\Controllers\Announcement\AnnouncementController@createAnnouncement',
        'App\Http\Controllers\Announcement\AnnouncementController@updatePublishedStatus',
        'App\Http\Controllers\Admin\ProjectController@createProject',
        'App\Http\Controllers\Admin\ProjectController@updateProject',
        'App\Http\Controllers\Admin\ProjectController@deleteProject',
        'App\Http\Controllers\Admin\ProjectController@assignProject',
        'App\Http\Controllers\Auth\PasswordResetController@sendPasswordResetLink',
        'App\Http\Controllers\Auth\PasswordResetController@passwordReset',
        'App\Http\Controllers\Auth\TwoFactorController@verifyTwoFactorCode',
    ];

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
        $activityStatus = false;

        if(Auth::check()){
            $requestLogId = $request['request_log_id'];

            if ($this->isActivityLoggingEnabled($request)) {
                $activityStatus = true;
            }

            $activityLogDto = new ActivityLogDTO($request, $requestLogId, $activityStatus, $response->getStatusCode());
            
            ActivityLog::create($activityLogDto->toArray());
        }
        return $response;
    }

    /**
     * Check if activity logging is enabled for the current request.
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isActivityLoggingEnabled(Request $request): bool
    {
        $actionName = $request->route() ? $request->route()->getActionName() : null;

        return in_array($actionName, $this->activityPaths);
    }
}
