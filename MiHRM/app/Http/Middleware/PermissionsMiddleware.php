<?php

namespace App\Http\Middleware;

use App\GlobalVariables\PermissionVariables;
use App\Helpers\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionsMiddleware
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
        $authUser = auth()->user();

        if(!$authUser){
            return Helpers::result("Unauthorized", Response::HTTP_BAD_REQUEST, []);
        }

        $authUserPermissions = $authUser->getAllPermissions()->pluck('name')->toArray();
        $path = str_replace('api', '', $request->path());

        $allPermissionVariables = [
            PermissionVariables::$login,
            PermissionVariables::$logout,
            PermissionVariables::$passwordSetup,
            PermissionVariables::$passwordReset,
            PermissionVariables::$passwordResetLink,
            PermissionVariables::$getEmployeeWorkingHours,
            PermissionVariables::$register,
            PermissionVariables::$getEmployeesByDepartment,
            PermissionVariables::$deleteUser,
            PermissionVariables::$updateEmployee,
            PermissionVariables::$getAllDepartments,
            PermissionVariables::$createProject,
            PermissionVariables::$updateProject,
            PermissionVariables::$deleteProject,
            PermissionVariables::$addDepartment,
            PermissionVariables::$assignProject,
            PermissionVariables::$getAllAssignedProjects,
            PermissionVariables::$handleLeaveRequest,
            PermissionVariables::$getLeaveRequest,
            PermissionVariables::$getAllEmployees,
            PermissionVariables::$getEmployeeRoleCounts,
            PermissionVariables::$getAllProjects,
            PermissionVariables::$submitLeaveRequest,
            PermissionVariables::$checkIn,
            PermissionVariables::$checkOut,
            PermissionVariables::$getSalaryDetails,
            PermissionVariables::$getAssignedProjects,
            PermissionVariables::$updateProjectStatus,
            PermissionVariables::$getEmployeesAttendence,
        ];

        // $permissions = array_column($allPermissionVariables, 'permission');

        foreach ($allPermissionVariables as $permissionArray) {
            if ($permissionArray['path'] === $path) {
                if (!in_array($permissionArray['permission'], $authUserPermissions)) {
                    return Helpers::result('You donot have the permission to access this route', Response::HTTP_FORBIDDEN, []);
                }
            }
        }
        
        return $next($request);
    }
}
