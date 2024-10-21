<?php

namespace App\Services\Auth;

use App\Constants\Messages;
use App\Helpers\Helpers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorService
{
    /**
     * Summary of verifyTwoFactorCode
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyTwoFactorCode($request){
        try{
            $request->validate([
                'two_fa_code' => 'required|digits:6',
            ]);
        
            $user = Auth::user();
            if (!$user) {
                return Helpers::result(Messages::UserNotAuthenticated, Response::HTTP_UNAUTHORIZED);
            }
        
            $google2fa = app('pragmarx.google2fa');
            $loginSecurity = $user->loginSecurity;
        
            $valid = $google2fa->verifyKey($loginSecurity->google2fa_secret, $request->two_fa_code);
        
            if ($valid) {
                if (!$loginSecurity->qr_code_scanned) {
                    $loginSecurity->qr_code_scanned = true;
                    $loginSecurity->google2fa_enable = true;
                    $loginSecurity->save();
                }
        
                $roles = $user->getRoleNames();
                $permissions = $user->getAllPermissions()->pluck('name');
                $employee = $user->employee;
        
                $userData = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $roles->first(),
                    'position' => $employee->position ?? null,
                    'department' => $employee->department->name ?? null,
                    'permissions' => $permissions,
                ];
        
                $token = auth()->refresh();
                $tokenData = Helpers::respondWithToken($token);
                $responseData = array_merge($tokenData, $userData);
        
                return Helpers::result(Messages::UserLoggedIn, Response::HTTP_OK, $responseData);
            } else {
                return Helpers::result(Messages::InvalidCode, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }catch (\Throwable $e) 
        {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}