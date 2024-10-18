<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorService;

class TwoFactorController extends Controller
{
    protected $twoFactorService;
    public function __construct(TwoFactorService $twoFactorService){
        $this->twoFactorService = $twoFactorService;
    }

    public function verifyTwoFactorCode(Request $request){
        // dd('working');
        return $this->twoFactorService->verifyTwoFactorCode($request);
    }
}
