<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\Salary;
use App\Helpers\Helpers;
use App\Models\Employee;

use App\DTOs\AuthDTOs\RegisterDTO;
use App\DTOs\EmployeeDTOs\SalaryCreateDTO;
use App\DTOs\EmployeeDTOs\EmployeeCreateDTO;

use Illuminate\Support\Facades\DB;

class AuthService
{

    /**
     * Summary of register
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register($request)
    {
        DB::beginTransaction();

        try {
            $dto = new RegisterDTO($request);
            $user = User::create($dto->toArray());

            $user->assignRole($dto->role);

            $employeeDto = new EmployeeCreateDTO($request, $user->id);
            $employee = Employee::create($employeeDto->toArray());

            $salaryDto = new SalaryCreateDTO($request, $employee->id);  
            Salary::create($salaryDto->toArray());

            DB::commit();

            return Helpers::result("User, Employee, and Salary registered successfully", 200, [
                'user' => $user,
                'employee' => $employee
            ]);
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return Helpers::result("Registration failed: " . $e->getMessage(), 500);
        }
    }

    /**
     * Summary of login
     * @param array $credentials
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(array $credentials)
    {
        
        $token = auth()->attempt($credentials);
        if (!$token) {
            return Helpers::result("Unauthorized", 401, ['error' => 'Invalid credentials']);
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
        $responseData = array_merge( $tokenData,$userData);
        return Helpers::result("User logged in successfully", 200, $responseData);
    }


    /**
     * Summary of logout
     * @return string[]
     */
    public function logout()
    {
        auth()->logout();
        return Helpers::result('User successfully logged out', 200);
    }

}
