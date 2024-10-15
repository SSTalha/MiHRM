<?php

namespace App\Services\Auth;

use App\Jobs\SendPasswordSetupEmailJob;
use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\DTOs\AuthDTOs\RegisterDTO;
use Illuminate\Support\Facades\DB;
use App\DTOs\EmployeeDTOs\EmployeeCreateDTO;
use Str;
use Symfony\Component\HttpFoundation\Response;

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
    } 
    catch (\Exception $e) 
    {
        DB::rollBack();
        return Helpers::result("Registration failed: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


    /**
     * Summary of login
     * @param array $credentials
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(array $credentials)
    {
        try{
            $token = auth()->attempt($credentials);

            if (!$token) {
                return Helpers::result("Unauthorized", Response::HTTP_BAD_REQUEST, ['error' => 'Invalid credentials']);
            }

            $user = auth()->user();
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

            $tokenData = Helpers::respondWithToken($token);
            $responseData = array_merge($tokenData, $userData);

            return Helpers::result("User logged in successfully", Response::HTTP_OK, $responseData);
        }catch(\Exception $e){
            return Helpers::result("Login failed: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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
