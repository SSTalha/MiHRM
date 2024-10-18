<?php

namespace App\Helpers;
use App\DTOs\LogsDTOs\ActivityLogDTO;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ActivityLogHelper
{
    public static function logActivity(Request $request, $activityStatus = false){
        if(Auth::check()) {
            $user = Auth::user();
            $activityLogDto = new ActivityLogDTO($request, $user->id, $activityStatus, Response::HTTP_OK);

            ActivityLog::create($activityLogDto->toArray());
    }
}
}