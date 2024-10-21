<?php

namespace App\Http\Controllers\Announcement;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Announcement\AnnouncementService;
use App\Http\Requests\Admin\CreateAnnouncementRequest;
use App\Http\Requests\Admin\UpdatePublishedStatusRequest;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function createAnnouncement(CreateAnnouncementRequest $request)
    {
        return $this->announcementService->createAnnouncement($request);
    }
    public function updatePublishedStatus(UpdatePublishedStatusRequest $request, $id): JsonResponse
    {
        return $this->announcementService->updatePublishedStatus($id, $request->validated());
    }
    public function getAnnouncements(Request $request): JsonResponse
    {
        return $this->announcementService->getAnnouncements($request);
    }
}
