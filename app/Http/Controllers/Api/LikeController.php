<?php

namespace App\Http\Controllers\Api;

use App\Events\StoryLiked;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Story $story): JsonResponse
    {
        $user = $request->user();

        $existing = Like::where('story_id', $story->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $story->decrement('likes_count');

            return response()->json(['liked' => false, 'likes_count' => $story->likes()->count()]);
        }

        Like::create([
            'story_id' => $story->id,
            'user_id' => $user->id,
        ]);

        $story->increment('likes_count');
        
        $like = Like::where('story_id', $story->id)->where('user_id', $user->id)->first();

        // Dispatch event for notifications
        StoryLiked::dispatch($like->load('user'));

        return response()->json(['liked' => true, 'likes_count' => $story->likes()->count()]);
    }
}
