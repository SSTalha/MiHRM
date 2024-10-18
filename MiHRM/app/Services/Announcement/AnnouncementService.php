<?php

namespace App\Services\Announcement;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Announcement;
use App\DTOs\AnnouncementDTOs\AnnouncementDTO;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementService
{
    public function createAnnouncement($request)
    {
        try {
            $user = auth()->user();
            $dto = new AnnouncementDTO($request, $user->id);

            $announcement = Announcement::create($dto->toArray());

            return Helpers::result("Announcement created successfully.", Response::HTTP_CREATED, $announcement);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePublishedStatus($announcementId, $data)
    {
        try {
            $announcement = Announcement::findOrFail($announcementId);

            $announcement->update([
                'is_published' => $data['is_published'],
                'published_at' => Carbon::now(),
            ]);

            return Helpers::result("Announcement status updated successfully.", Response::HTTP_OK, $announcement);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAnnouncements()
    {
        try {
            $user = auth()->user();
            if ($user->hasRole('admin') || $user->hasRole('hr')) {
                $announcements = Announcement::all();
            } else {
                $announcements = Announcement::where('is_published', true)->get();
            }
            if ($announcements->isEmpty()) {
                return Helpers::result("No announcements found.", Response::HTTP_NOT_FOUND);
            }

            return Helpers::result("Announcements retrieved successfully.", Response::HTTP_OK, $announcements);
        } catch (\Exception $e) {
            return Helpers::result("An error occurred: " . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
