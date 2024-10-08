<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;



Route::group(['middleware'=>['api', 'log.request']], function(){
    Route::post('/register', [AuthController::class, 'register']); // Registration route
    Route::post('/login', [AuthController::class, 'login']); // Login route
    Route::post('/logout', [AuthController::class, 'logout']); // Logout route
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut']);


    Route::group(['middleware' => ['jwt.auth']], function(){
        //Jwt route goes here
    });
});