<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::group(['middleware'=>['api', 'log.request']], function(){
    Route::post('/register', [AuthController::class, 'register']); // Registration route
    Route::post('/login', [AuthController::class, 'login']); // Login route
    Route::post('/logout', [AuthController::class, 'logout']); // Logout route

    Route::group(['middleware' => ['jwt.auth']], function(){
        //Jwt route goes here
    });
});