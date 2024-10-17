<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    protected $twoFactorService;
    public function __construct(TwoFactorService $twoFactorService){
        $this->twoFactorService = $twoFactorService;
    }

    public function verifyTwoFactorCode(Request $request){
        return $this->twoFactorService->verifyTwoFactorCode($request);
    }
}
