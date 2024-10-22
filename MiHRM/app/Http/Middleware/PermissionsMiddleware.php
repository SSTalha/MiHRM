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

        if (!$authUser) {
            return Helpers::result("Unauthorized", Response::HTTP_BAD_REQUEST);
        }

        $authUserPermissions = $authUser->getAllPermissions()->pluck('name')->toArray();
        $path = str_replace('api', '', $request->path());

        $allPermissionVariables = PermissionVariables::getPermissionEndpoints();

        // $permissions = array_column($allPermissionVariables, 'permission');

        foreach ($allPermissionVariables as $permissionArray) {
            $prefixedPath = !empty($permissionArray['prefix'])
            ? trim($permissionArray['prefix'], '/') . $permissionArray['path']
            : $permissionArray['path'];

            if ($prefixedPath === $path || $permissionArray['path'] === $path) {
                if (!isset($permissionArray['permission'])) {
                    return $next($request);
                }elseif(!in_array($permissionArray['permission'], $authUserPermissions)) {
                    return Helpers::result('You donot have the permission to access this route', Response::HTTP_FORBIDDEN);
                }
            }
        }

        return $next($request);
    }
}
