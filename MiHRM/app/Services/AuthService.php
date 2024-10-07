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
    // Creating the DTO instance for user registration
    $dto = new RegisterDTO($request);

    // Create the user using the DTO data
    $user = User::create($dto->toArray());

    // Assign the role from the DTO
    $user->assignRole($dto->role);

    // Creating the EmployeeDTO instance to create the employee record
    $employeeDto = new EmployeeCreateDTO($request, $user->id);

    // Create an employee record with the EmployeeDTO data
    $employee = Employee::create($employeeDto->toArray());

    // Return a success response with both user and employee information
    return Helpers::result("User and Employee registered successfully", 200, [
        'user' => $user,
        'employee' => $employee,
    ]);
}

    // ############### Login Methode #################
   public function login(array $credentials)
{
    // Attempt to authenticate using the provided credentials
    if (!auth()->attempt($credentials)) {
        return Helpers::result("Unauthorized", 401, ['error' => 'Invalid credentials']);
    }

    // Now that the user is authenticated, retrieve the authenticated user
    $user = auth()->user();

    // Get the role names and permissions
    $roles = $user->getRoleNames(); // Get the roles assigned to the user
    $permissions = $user->getAllPermissions()->pluck('name'); // Get all permissions assigned to the user

    // Assume the Employee model is related to the User model and can be accessed like this
    $employee = $user->employee; // Assuming the user has an associated employee record

    // Prepare the response data
    $responseData = [
        'name' => $user->name,
        'email' => $user->email,
        'role' => $roles->first(), // Get the first role name (you can adjust based on your needs)
        'permissions' => $permissions,
        'position' => $employee->position ?? null, // Optional: Use null if not found
        'department' => $employee->department->name ?? null, // Assuming the employee has a department relationship
    ];

    // Return the user data in the response
    return Helpers::result("User logged in successfully", 200, $responseData);
}


    // ############### Logout Methode #################
    public function logout()
    {
        auth()->logout();
        return ['message' => 'User successfully logged out!'];
    }

    
}
