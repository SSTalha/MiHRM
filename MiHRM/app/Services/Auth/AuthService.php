<?php

namespace App\Services\Auth;

use App\Jobs\SendPasswordSetupEmailJob;
use App\Models\LoginSecurity;
use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\DTOs\AuthDTOs\RegisterDTO;
use Illuminate\Support\Facades\DB;
use App\DTOs\EmployeeDTOs\EmployeeCreateDTO;
use Symfony\Component\HttpFoundation\Response;
use Str;

class AuthService
{

    /**
 * Summary of register
 * @param mixed $request
 * @return mixed|\Illuminate\Http\JsonResponse
 */
public function register($request)
{
    try {
        $dto = new RegisterDTO($request);
        $user = User::create($dto->toArray());

        $user->assignRole($dto->role);

        $employeeDto = new EmployeeCreateDTO($request, $user->id);
        $employee = Employee::create($employeeDto->toArray());

        $token = Str::random(40);
        $user->remember_token = $token;
        $user->save();

        SendPasswordSetupEmailJob::dispatch($user, $token);

        return Helpers::result("User, Employee registered successfully", Response::HTTP_OK, [
            'user' => $user,
            'employee' => $employee
        ]);
    } catch (\Exception $e) 
    {
        DB::rollBack();
        return Helpers::result("Registration failed: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    /**
     * Summary of login
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login($request)
    {
        try{
            $credentials = $request->only('email', 'password');
    
            if (!auth()->attempt($credentials)) {
                return Helpers::result('Invalid credentials', Response::HTTP_UNAUTHORIZED);
            }
            $user = auth()->user();
            $loginSecurity = $user->loginSecurity;
            if (!$loginSecurity) {
                $loginSecurity = new LoginSecurity();
                $loginSecurity->user_id = $user->id;
            }
        
            if ($loginSecurity->google2fa_enable) {
                $token = auth()->tokenById($user->id);
                return Helpers::result('Please enter your 2FA code from Google Authenticator.', Response::HTTP_OK, $token);
            } else {
                $google2fa = app('pragmarx.google2fa');
                $secretKey = $google2fa->generateSecretKey();
        
                $loginSecurity->google2fa_secret = $secretKey;
                $loginSecurity->qr_code_scanned = false;
                $loginSecurity->save();
        
                $QRImage = $google2fa->getQRCodeInline(
                    config('app.name'),
                    $user->email,
                    $secretKey
                );
                $token = auth()->tokenById($user->id);
                $data = [
                    'qr_code' => $QRImage,
                    'secret' => $secretKey,
                    'token' => $token
                ];
                return Helpers::result('Scan the QR code to set up 2FA.', Response::HTTP_OK, $data);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return Helpers::result("Registration failed: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of logout
     * @return string[]
     */
    public function logout()
    {
        auth()->logout();
        return Helpers::result('User successfully logged out', Response::HTTP_OK);
    }

}
