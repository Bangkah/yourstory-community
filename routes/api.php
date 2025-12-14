<?php

use App\Http\Controllers\Api\Admin\StoryController as AdminStoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\StoryController;
use Illuminate\Support\Facades\Route;

// Public endpoints with rate limiting
Route::middleware('throttle:60,1')->group(function () {
    Route::get('stories', [StoryController::class, 'index']);
    Route::get('stories/{story}', [StoryController::class, 'show']);
    Route::get('stories/{story}/comments', [CommentController::class, 'index']);

    // Public user endpoints
    Route::get('users/{user}/followers', [FollowerController::class, 'followers']);
    Route::get('users/{user}/following', [FollowerController::class, 'following']);
    Route::get('users/{user}/follow-counts', [FollowerController::class, 'counts']);
    Route::get('users/{user}/follow-status', [FollowerController::class, 'checkFollowStatus']);
});

// Auth endpoints with stricter rate limiting
Route::post('auth/login', [LoginController::class, 'login'])->middleware('throttle:5,1');

// Protected endpoints (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Standard rate limit: 120 requests per minute
    Route::middleware('throttle:120,1')->group(function () {
        Route::post('auth/logout', [LoginController::class, 'logout']);
        Route::get('auth/me', [LoginController::class, 'me']);

        Route::get('stories', [StoryController::class, 'index']);
        Route::get('stories/{story}', [StoryController::class, 'show']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::put('notifications/{notificationId}/read', [NotificationController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('notifications/{notificationId}', [NotificationController::class, 'destroy']);
    });

    // Content creation: 30 requests per minute (more restrictive)
    Route::middleware('throttle:30,1')->group(function () {
        Route::post('stories', [StoryController::class, 'store']);
        Route::put('stories/{story}', [StoryController::class, 'update']);
        Route::delete('stories/{story}', [StoryController::class, 'destroy']);

        Route::post('stories/{story}/comments', [CommentController::class, 'store']);
        Route::post('stories/{story}/comments/{comment}/reply', [CommentController::class, 'reply']);
    });

    // Social interactions: 60 requests per minute
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('stories/{story}/likes/toggle', [LikeController::class, 'toggle']);
        Route::post('users/{user}/follow', [FollowerController::class, 'follow']);
        Route::delete('users/{user}/follow', [FollowerController::class, 'unfollow']);
    });

    // Admin endpoints with strict rate limiting
    Route::middleware(['admin', 'throttle:60,1'])->group(function () {
        Route::get('admin/users', [AdminUserController::class, 'index']);
        Route::get('admin/users/{user}', [AdminUserController::class, 'show']);
        Route::put('admin/users/{user}/role', [AdminUserController::class, 'updateRole']);
        Route::post('admin/users/{user}/suspend', [AdminUserController::class, 'toggleSuspend']);
        Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy']);

        Route::get('admin/stories', [AdminStoryController::class, 'index']);
        Route::get('admin/stories/{story}', [AdminStoryController::class, 'show']);
        Route::put('admin/stories/{story}/status', [AdminStoryController::class, 'updateStatus']);
        Route::delete('admin/stories/{story}', [AdminStoryController::class, 'destroy']);

        // Soft delete endpoints
        Route::get('admin/stories/trashed', [StoryController::class, 'trashed']);
        Route::post('admin/stories/{id}/restore', [StoryController::class, 'restore']);
        Route::delete('admin/stories/{id}/force-delete', [StoryController::class, 'forceDelete']);
    });
});




