<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordRequest;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService){
        $this->passwordResetService = $passwordResetService;
    }

    public function sendPasswordResetLink(Request $request){
        $request->validate(['email' => 'required|email']);

        return $this->passwordResetService->sendPasswordResetLink($request);
    }

    public function passwordReset(PasswordRequest $request){
        $data = $request->only(['email','token','password']);
        return $this->passwordResetService->passwordReset($data);
    }
}
