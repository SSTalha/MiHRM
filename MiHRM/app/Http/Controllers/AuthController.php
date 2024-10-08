<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest; // Import your RegisterRequest
use App\Http\Requests\LoginRequest; // Import a LoginRequest if you have one
use App\Services\AuthService; // Import your AuthService
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService; // Service for handling authentication

    // Constructor to inject AuthService
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService; // Assigning the injected service
    }

    /**
     * Handle user registration.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Pass validated data to the service method
        return $this->authService->register($request->validated());
    }

    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->validated());

    
    }

    /**
     * Handle user logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $response = $this->authService->logout();
        return response()->json($response);
    }
}
