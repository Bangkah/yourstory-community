<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $query = Story::query()
            ->with(['user:id,name,role'])
            ->withCount(['likes', 'allComments as comments_total']);

        // Search by title or body
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Filter by author
        if ($request->filled('author')) {
            $author = $request->string('author');
            $query->whereHas('user', function ($q) use ($author) {
                $q->where('name', 'like', "%{$author}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $role = $request->string('role');
            $query->whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        if (!$request->user()) {
            $query->where('is_published', true);
        }

        if ($request->boolean('only_published')) {
            $query->where('is_published', true);
        }

        // Sorting
        $sort = $request->string('sort', 'latest');
        match ($sort) {
            'latest' => $query->latest(),
            'oldest' => $query->oldest(),
            'most_liked' => $query->orderBy('likes_count', 'desc'),
            'most_commented' => $query->orderBy('comments_count', 'desc'),
            default => $query->latest(),
        };

        $stories = $query->paginate($perPage);

        return response()->json($stories);
    }

    public function show(Request $request, Story $story): JsonResponse
    {
        $this->authorize('view', $story);

        $story->load(['user:id,name,role'])->loadCount(['likes', 'allComments as comments_total']);

        return response()->json($story);
    }

    public function store(StoreStoryRequest $request): JsonResponse
    {
        $this->authorize('create', Story::class);

        $data = $request->validated();

        $story = Story::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
            'is_published' => $data['is_published'] ?? true,
        ]);

        return response()->json($story, 201);
    }

    public function update(UpdateStoryRequest $request, Story $story): JsonResponse
    {
        $this->authorize('update', $story);

        $story->fill($request->validated());
        $story->save();

        return response()->json($story);
    }

    public function destroy(Request $request, Story $story): JsonResponse
    {
        $this->authorize('delete', $story);

        $story->delete();

        return response()->json(['message' => 'Story deleted']);
    }

    /**
     * List deleted stories (admin only)
     */
    public function trashed(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $perPage = min($request->integer('per_page', 15), 100);

        $stories = Story::onlyTrashed()
            ->with(['user:id,name,role'])
            ->withCount(['likes', 'allComments as comments_total'])
            ->latest('deleted_at')
            ->paginate($perPage);

        return response()->json($stories);
    }

    /**
     * Restore a deleted story
     */
    public function restore(Request $request, $storyId): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $story = Story::withTrashed()->findOrFail($storyId);

        if (!$story->trashed()) {
            return response()->json(['message' => 'Story is not deleted'], 400);
        }

        $story->restore();

        return response()->json([
            'message' => 'Story restored successfully',
            'data' => $story,
        ]);
    }

    /**
     * Permanently delete a story
     */
    public function forceDelete(Request $request, $storyId): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $story = Story::withTrashed()->findOrFail($storyId);
        $storyTitle = $story->title;
        $story->forceDelete();

        return response()->json([
            'message' => "Story '{$storyTitle}' permanently deleted",
            'data' => [
                'deleted_story_id' => $storyId,
            ],
        ]);
    }
}
