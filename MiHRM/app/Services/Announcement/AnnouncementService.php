<?php

namespace App\Services\Announcement;

use App\Constants\Messages;
use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Announcement;
use App\DTOs\AnnouncementDTOs\AnnouncementDTO;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementService
{
    /**
     * Summary of createAnnouncement
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function createAnnouncement($request)
    {
        try {
            $user = auth()->user();
            $dto = new AnnouncementDTO($request, $user->id);

            $announcement = Announcement::create($dto->toArray());

            return Helpers::result("Announcement created successfully.", Response::HTTP_CREATED, $announcement);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of updatePublishedStatus
     * @param mixed $announcementId
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function updatePublishedStatus($request)
    {
        try {
            $announcementId=$request['id'];
            $announcement = Announcement::findOrFail($announcementId);

            $announcement->update([
                'is_published' => $request['is_published'],
                'published_at' => Carbon::now(),
            ]);

            return Helpers::result("Announcement status updated successfully.", Response::HTTP_OK, $announcement);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of getAnnouncements
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAnnouncements($request)
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
            $data = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'user_id' => $announcement->user_id,
                    'user_name' => $announcement->user->name, 
                    'title' => $announcement->title,
                    'text' => $announcement->text,
                    'is_published' => $announcement->is_published,
                    'published_at' => $announcement->published_at,
                ];
            });

            return Helpers::result("Announcements retrieved successfully.", Response::HTTP_OK, $data);
        }catch (\Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}
