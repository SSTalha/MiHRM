<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Announcement;
use Illuminate\Console\Command;

class PublishAnnouncements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:publish-announcements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled announcements';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch announcements that need to be published
        $announcements = Announcement::where('is_published', false)
                                      ->where('published_at', '<=', Carbon::now())
                                      ->get();

        foreach ($announcements as $announcement) {
            // Update each announcement to mark it as published
            $announcement->update([
                'is_published' => true,
            ]);

            // Optionally, log to the console for feedback
            $this->info('Published Announcement: ' . $announcement->title);
        }

        if ($announcements->isEmpty()) {
            $this->info('No announcements to publish at this time.');
        }
        return 0;  
    }
}
