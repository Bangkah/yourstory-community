<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentNotification implements ShouldQueue
{
    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        
        // Notify story author
        if ($comment->story->user_id !== $comment->user_id) {
            Notification::create([
                'user_id' => $comment->story->user_id,
                'type' => 'comment_created',
                'data' => [
                    'comment_id' => $comment->id,
                    'story_id' => $comment->story_id,
                    'user_name' => $comment->user->name,
                    'comment_preview' => substr($comment->body, 0, 100),
                ],
            ]);
        }

        // Notify parent comment author (if reply)
        if ($comment->parent_id && $comment->parent->user_id !== $comment->user_id) {
            Notification::create([
                'user_id' => $comment->parent->user_id,
                'type' => 'comment_reply',
                'data' => [
                    'comment_id' => $comment->id,
                    'parent_id' => $comment->parent_id,
                    'story_id' => $comment->story_id,
                    'user_name' => $comment->user->name,
                    'reply_preview' => substr($comment->body, 0, 100),
                ],
            ]);
        }
    }
}
