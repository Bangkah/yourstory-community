<?php

namespace App\Listeners;

use App\Events\StoryLiked;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLikeNotification implements ShouldQueue
{
    public function handle(StoryLiked $event): void
    {
        $like = $event->like;
        
        // Notify story author
        if ($like->story->user_id !== $like->user_id) {
            Notification::create([
                'user_id' => $like->story->user_id,
                'type' => 'story_liked',
                'data' => [
                    'story_id' => $like->story_id,
                    'user_name' => $like->user->name,
                    'story_title' => $like->story->title,
                ],
            ]);
        }
    }
}
