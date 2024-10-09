<?php

namespace App\Services;

use App\Models\User;
use App\Models\Salary;
use App\Helpers\Helpers;
use App\Models\Employee;

use App\DTOs\RegisterDTO;
use App\DTOs\SalaryCreateDTO;
use Illuminate\Support\Facades\DB;
use App\DTOs\EmployeeCreateDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthService
{

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

            // Return an error response
            return Helpers::result("Registration failed: " . $e->getMessage(), 500);
        }
    }

    // ############### Login Method #################

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


    // ############### Logout Method #################
    public function logout()
    {
        auth()->logout();
        return ['message' => 'User successfully logged out!'];
    }

    
}
