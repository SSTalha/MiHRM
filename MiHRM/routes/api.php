<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\WorkingHourController;



Route::group(['middleware' => ['api', 'log.request','log.activity']], function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('/salary/{employeeId}', [SalaryController::class, 'getSalaryDetails']);
    
    Route::group(['middleware' => ['role:admin']], function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::get('/get-employees/department/{department_id}', [AdminController::class, 'getEmployeesByDepartment']);
            Route::delete('/delete-employees/{user_id}', [AdminController::class, 'deleteUser']);
            Route::put('/update-employees/update/{employee_id}', [AdminController::class, 'updateEmployee']);
            Route::get('/get/departments', [AdminController::class, 'getAllDepartments']);
            Route::post('/create-project', [AdminController::class, 'createProject']);
        });

            Route::group(['middleware' => ['role:hr|employee']], function () {
            Route::post('/submit/leave', [EmployeeController::class, 'submitLeaveRequest']);
            Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
            Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);
        });
        
        Route::group(['middleware' => ['role:hr']], function () {
            Route::post('/project-assignments', [AdminController::class, 'assignProject']);
            Route::get('/projects-all', [AdminController::class, 'getAllProjects']);
        });
        
        Route::group(['middleware' => ['role:admin|hr']], function () {
            Route::get('/get-assigned-projects', [AdminController::class, 'getAllAssignedProjects']);
            Route::post('/leave-requests/{leaveRequestId}/{status}', [AdminController::class, 'handleLeaveRequest']);
            Route::get('/get-employees-attendence', [AttendanceController::class, 'getEmployeesAttendence']);
            Route::get('/salaries', [SalaryController::class, 'getAllSalaries']);
        });

        Route::group(['middleware' => ['role:employee']], function () {
            Route::get('/get-employee/assigned-projects', [EmployeeController::class, 'getAssignedProjects']);
            Route::get('/get-employee/working-hours', [WorkingHourController::class, 'getWorkingHours']); 
            Route::post('/projects/update-status', [EmployeeController::class, 'updateProjectStatus']);
        });

    });
});
