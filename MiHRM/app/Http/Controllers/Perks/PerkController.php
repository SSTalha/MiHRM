<?php

namespace App\Http\Controllers\Perks;

use Illuminate\Http\JsonResponse;
use App\Services\Perks\PerkService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Perks\PerksRequest;
use App\Http\Requests\Perks\CreatePerkRequest;
use App\Http\Requests\Perks\HandlePerksRequest;

class PerkController extends Controller
{
    protected $perkService;

    public function __construct(PerkService $perkService)
    {
        $this->perkService = $perkService;
    }

    public function createPerk(CreatePerkRequest $request)
    {
        return $this->perkService->createPerk($request);
    }


    public function requestPerks(PerksRequest $request)
    {
        return $this->perkService->requestPerks($request);
    }


    public function handlePerkRequest(HandlePerksRequest $request)
    {
        return $this->perkService->handlePerkRequest($request);
    }
    public function getAllPerks(): JsonResponse
    {
        return $this->perkService->getAllPerks();
    }
    public function getAllPerkRequests(): JsonResponse
    {
        return $this->perkService->getAllPerkRequests();
    }
}
