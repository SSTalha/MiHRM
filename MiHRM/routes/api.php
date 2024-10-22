<?php

use Illuminate\Support\Facades\Route;
use App\GlobalVariables\PermissionVariables;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Perks\PerkController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\WorkingHourController;
use App\Http\Controllers\Employee\LeaveRequestController;
use App\Http\Controllers\Announcement\AnnouncementController;


Route::group(['middleware' => ['api', 'log.request', 'log.activity']], function () {

    Route::post(PermissionVariables::$login['path'], [AuthController::class, 'login']);
    Route::post(PermissionVariables::$logout['path'], [AuthController::class, 'logout']);
    Route::post(PermissionVariables::$passwordSetup['path'], [EmployeeController::class, 'passwordSetup']);
    Route::post(PermissionVariables::$passwordReset['path'], [PasswordResetController::class, 'passwordReset']);
    Route::post(PermissionVariables::$passwordResetLink['path'], [PasswordResetController::class, 'sendPasswordResetLink']);


    Route::group(['middleware' => ['jwt', 'routes.permission']], function () {
        Route::post(PermissionVariables::$verifyTwoFactorCode['path'], [TwoFactorController::class, 'verifyTwoFactorCode']);


        // Admin-specific routes
        Route::group(['middleware' => ['role:admin']], function () {
            Route::post(PermissionVariables::$register['path'], [AuthController::class, 'register']);
            Route::delete(PermissionVariables::$deleteUser['path'], [AdminController::class, 'deleteUser']);
            Route::post(PermissionVariables::$createProject['path'], [ProjectController::class, 'createProject']);
            Route::delete(PermissionVariables::$deleteProject['path'], [ProjectController::class, 'deleteProject']);
            Route::post(PermissionVariables::$addDepartment['path'], [AdminController::class, 'addDepartment']);
            Route::put(PermissionVariables::$updateEmployee['path'], [AdminController::class, 'updateEmployee']);
            Route::post('/perks-create', [PerkController::class, 'createPerk']);
        });

        // HR-specific routes
        Route::group(['middleware' => ['role:hr']], function () {
            Route::post(PermissionVariables::$assignProject['path'], [ProjectController::class, 'assignProject']);
        });

        // Admin and HR common routes
        Route::group(['middleware' => ['role:admin|hr']], function () {
            Route::get(PermissionVariables::$getEmployeesByDepartment['path'], [AdminController::class, 'getEmployeesByDepartment']);
            Route::get(PermissionVariables::$getAllDepartments['path'], [AdminController::class, 'getAllDepartments']);
            Route::put(PermissionVariables::$updateProject['path'], [ProjectController::class, 'updateProject']);
            Route::get(PermissionVariables::$getAllAssignedProjects['path'], [ProjectController::class, 'getAllAssignedProjects']);
            Route::post(PermissionVariables::$handleLeaveRequest['path'], [AdminController::class, 'handleLeaveRequest']);
            Route::get(PermissionVariables::$getAllEmployees['path'], [AdminController::class, 'getAllEmployees']);
            Route::get(PermissionVariables::$getEmployeeRoleCounts['path'], [AdminController::class, 'getEmployeeRoleCounts']);
            Route::get(PermissionVariables::$getAllProjects['path'], [ProjectController::class, 'getAllProjects']);
            Route::get(PermissionVariables::$getAllAttendance['path'], [WorkingHourController::class, 'getAllAttendanceRecords']);

            Route::get(PermissionVariables::$getProjectCount['path'], [ProjectController::class, 'getProjectCount']);
            Route::get(PermissionVariables::$getDailyAttendanceCount['path'], [AttendanceController::class, 'getDailyAttendanceCount']);

            Route::post(PermissionVariables::$createAnnouncement['path'], [AnnouncementController::class, 'createAnnouncement']);
            Route::put(PermissionVariables::$updatePublishedStatus['path'], [AnnouncementController::class, 'updatePublishedStatus']);
            Route::post('/perks/request-handle', [PerkController::class, 'handlePerkRequest']);
            Route::get('/get-perks/requests', [PerkController::class, 'getAllPerkRequests']);
        });

        // HR and Employee common routes
        Route::group(['middleware' => ['role:hr|employee']], function () {
            Route::post(PermissionVariables::$submitLeaveRequest['path'], [EmployeeController::class, 'submitLeaveRequest']);
            Route::post(PermissionVariables::$checkInCheckOut['path'], [AttendanceController::class, 'handleCheckInOut']);
            Route::get(PermissionVariables::$getAttendanceCount['path'], [AttendanceController::class, 'getAttendanceCount']);
            Route::post('/perks/request', [PerkController::class, 'requestPerks']);
        });

        // Admin, HR, and Employee common routes
        Route::group(['middleware' => ['role:admin|hr|employee']], function () {
            Route::get(PermissionVariables::$getEmployeeWorkingHours['path'], [WorkingHourController::class, 'getWorkingHours']);
            Route::get(PermissionVariables::$getLeaveRequest['path'], [LeaveRequestController::class, 'getLeaveRequest']);
            Route::get(PermissionVariables::$getEmployeesAttendence['path'], [AttendanceController::class, 'getEmployeesAttendence']);
            Route::get(PermissionVariables::$getSalaryDetails['path'], [SalaryController::class, 'getSalaryDetails']);
            Route::put(PermissionVariables::$updateUser['path'], [AdminController::class, 'updateUser']);
            Route::get(PermissionVariables::$getAnnouncements['path'], [AnnouncementController::class, 'getAnnouncements']);
            Route::get('/get-all-perks', [PerkController::class, 'getAllPerks']);
        });

        // Employee-specific routes
        Route::group(['middleware' => ['role:employee']], function () {
            Route::get(PermissionVariables::$getAssignedProjects['path'], [EmployeeController::class, 'getAssignedProjects']);
            Route::post(PermissionVariables::$updateProjectStatus['path'], [EmployeeController::class, 'updateProjectStatus']);
        });
    });
});
