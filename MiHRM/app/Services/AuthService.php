<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\DTOs\RegisterDTO;


use Illuminate\Support\Str;
use App\DTOs\EmployeeCreateDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthService
{

   public function register($request)
{
    $dto = new RegisterDTO($request);

    $user = User::create($dto->toArray());

    $user->assignRole($dto->role);

    $employeeDto = new EmployeeCreateDTO($request, $user->id);

    $employee = Employee::create($employeeDto->toArray());

    return Helpers::result("User and Employee registered successfully", 200, [
        'user' => $user,
        'employee' => $employee,
    ]);
}

    // ############### Login Method #################
   public function login(array $credentials)
{
    if (!auth()->attempt($credentials)) {
        return Helpers::result("Unauthorized", 401, ['error' => 'Invalid credentials']);
    }
    $user = auth()->user();
    $roles = $user->getRoleNames();
    $permissions = $user->getAllPermissions()->pluck('name');

    $employee = $user->employee;

    $responseData = [
        'name' => $user->name,
        'email' => $user->email,
        'role' => $roles->first(),
        'permissions' => $permissions,
        'position' => $employee->position ?? null,
        'department' => $employee->department->name ?? null,
    ];
    return Helpers::result("User logged in successfully", 200, $responseData);
}


    // ############### Logout Method #################
    public function logout()
    {
        auth()->logout();
        return ['message' => 'User successfully logged out!'];
    }

    
}
