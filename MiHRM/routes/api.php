<?php

use App\GlobalVariables\PermissionVariables;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\WorkingHourController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Employee\LeaveRequestController;





Route::group(['middleware' => ['api', 'log.request', 'log.activity']], function () {

    Route::post(PermissionVariables::$login['path'], [AuthController::class, 'login']);
    Route::post(PermissionVariables::$logout['path'], [AuthController::class, 'logout']);
    Route::post(PermissionVariables::$passwordSetup['path'], [EmployeeController::class, 'passwordSetup']);
    Route::post(PermissionVariables::$passwordReset['path'], [PasswordResetController::class, 'passwordReset']);
    Route::post(PermissionVariables::$passwordResetLink['path'], [PasswordResetController::class, 'sendPasswordResetLink']);

    // Routes with JWT authentication and permission check middleware
    Route::group(['middleware' => ['jwt.auth', 'routes.permission']], function () {

        // Admin-specific routes
        Route::group(['middleware' => ['role:admin']], function () {
            Route::post(PermissionVariables::$register['path'], [AuthController::class, 'register']);
            Route::delete(PermissionVariables::$deleteUser['path'], [AdminController::class, 'deleteUser']);
            Route::post(PermissionVariables::$createProject['path'], [AdminController::class, 'createProject']);
            Route::delete(PermissionVariables::$deleteProject['path'], [AdminController::class, 'deleteProject']);
            Route::post(PermissionVariables::$addDepartment['path'], [AdminController::class, 'addDepartment']);
            Route::put(PermissionVariables::$updateEmployee['path'], [AdminController::class, 'updateEmployee']); //change func

        });

        // HR-specific routes
        Route::group(['middleware' => ['role:hr']], function () {
            Route::post(PermissionVariables::$assignProject['path'], [AdminController::class, 'assignProject']);
        });

        // Admin and HR common routes
        Route::group(['middleware' => ['role:admin|hr']], function () {
            Route::get(PermissionVariables::$getEmployeesByDepartment['path'], [AdminController::class, 'getEmployeesByDepartment']);
            Route::get(PermissionVariables::$getAllDepartments['path'], [AdminController::class, 'getAllDepartments']);
            Route::put(PermissionVariables::$updateProject['path'], [AdminController::class, 'updateProject']);
            Route::get(PermissionVariables::$getAllAssignedProjects['path'], [AdminController::class, 'getAllAssignedProjects']);
            Route::post(PermissionVariables::$handleLeaveRequest['path'], [AdminController::class, 'handleLeaveRequest']);
            Route::get(PermissionVariables::$getAllEmployees['path'], [AdminController::class, 'getAllEmployees']);
            Route::get(PermissionVariables::$getEmployeeRoleCounts['path'], [AdminController::class, 'getEmployeeRoleCounts']); //change func
            Route::get(PermissionVariables::$getAllProjects['path'], [AdminController::class, 'getAllProjects']);

        });

        // HR and Employee common routes
        Route::group(['middleware' => ['role:hr|employee']], function () {
            Route::post(PermissionVariables::$submitLeaveRequest['path'], [EmployeeController::class, 'submitLeaveRequest']);
            Route::post(PermissionVariables::$checkIn['path'], [AttendanceController::class, 'checkIn']);
            Route::post(PermissionVariables::$checkOut['path'], [AttendanceController::class, 'checkOut']);
        });

        // Admin, HR, and Employee common routes
        Route::group(['middleware' => ['role:admin|hr|employee']], function () {
            Route::get(PermissionVariables::$getEmployeeWorkingHours['path'], [WorkingHourController::class, 'getWorkingHours']);
            Route::get(PermissionVariables::$getLeaveRequest['path'], [LeaveRequestController::class, 'getLeaveRequest']);
            Route::get(PermissionVariables::$getEmployeesAttendence['path'], [AttendanceController::class, 'getEmployeesAttendence']);
            Route::get(PermissionVariables::$getSalaryDetails['path'], [SalaryController::class, 'getSalaryDetails']);
        });

        // Employee-specific routes
        Route::group(['middleware' => ['role:employee']], function () {
            Route::get(PermissionVariables::$getAssignedProjects['path'], [EmployeeController::class, 'getAssignedProjects']);
            Route::post(PermissionVariables::$updateProjectStatus['path'], [EmployeeController::class, 'updateProjectStatus']);
        });
    });
});
