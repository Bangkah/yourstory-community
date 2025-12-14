<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\StoryLiked;
use App\Listeners\SendCommentNotification;
use App\Listeners\SendLikeNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentCreated::class => [
            SendCommentNotification::class,
        ],
        StoryLiked::class => [
            SendLikeNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
