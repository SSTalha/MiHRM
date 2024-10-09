<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;



Route::group(['middleware' => ['api', 'log.request']], function () {

    Route::post('/login', [AuthController::class, 'login']); // Login route
    Route::post('/logout', [AuthController::class, 'logout']); // Logout route


    Route::group(['middleware' => ['jwt.auth']], function () {
        
        Route::group(['middleware' => ['role:admin']], function () {
            Route::post('/register', [AuthController::class, 'register']); // Registration route
            Route::get('/employees/department/{department_id}', [AdminController::class, 'getEmployeesByDepartment']);
            Route::delete('/employees/{user_id}', [AdminController::class, 'deleteUser']);
            Route::put('/employees/update/{employee_id}', [AdminController::class, 'updateEmployee']);
            Route::get('/get/departments', [AdminController::class , 'getAllDepartments']);
        });

        Route::post('/leave-requests/{leaveRequestId}/{status}', [AdminController::class, 'handleLeaveRequest']);
        Route::get('/attendance-report', [AttendanceController::class, 'getAbsentEmployees']);

        Route::group(['middleware' => ['role:hr|employee']], function () {
            Route::post('/submit/leave', [EmployeeController::class, 'submitLeaveRequest']);
            Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
            Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
            
        });


    });
});
