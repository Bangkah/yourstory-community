<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Story;
use App\Services\Firebase\FirestoreCommentBroadcaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CommentController extends Controller
{
    public function index(Story $story): JsonResponse
    {
        $comments = $story->allComments()
            ->with(['user:id,name,role'])
            ->orderBy('created_at')
            ->get();

        $tree = $this->buildTree($comments);

        return response()->json($tree);
    }

    public function store(StoreCommentRequest $request, Story $story): JsonResponse
    {
        $this->authorize('view', $story);

        $data = $request->validated();

        $parentId = $request->input('parent_id');
        $parentId = $parentId !== null ? (int) $parentId : null;
        $parent = null;

        if ($parentId) {
            $parent = Comment::where('story_id', $story->id)->findOrFail($parentId);
        }

        $comment = Comment::create([
            'story_id' => $story->id,
            'user_id' => $request->user()->id,
            'parent_id' => $parent?->id,
            'body' => $data['body'],
            'depth' => $parent ? $parent->depth + 1 : 0,
        ]);

        $story->increment('comments_count');

        app(FirestoreCommentBroadcaster::class)->broadcast($comment->load('user'));

        // Dispatch event for notifications
        CommentCreated::dispatch($comment->load('user'));

        return response()->json($comment->load('user'), 201);
    }

    public function reply(StoreCommentRequest $request, Story $story, Comment $comment): JsonResponse
    {
        if ($comment->story_id !== $story->id) {
            abort(404);
        }

        $request->merge(['parent_id' => $comment->id]);

        return $this->store($request, $story);
    }

    private function buildTree(Collection $comments): array
    {
        $grouped = $comments->groupBy(fn (Comment $comment) => $comment->parent_id ?? 0);

        $build = function ($parentId) use (&$build, $grouped) {
            return ($grouped[$parentId] ?? collect())->map(function (Comment $comment) use (&$build) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'depth' => $comment->depth,
                    'user' => $comment->user,
                    'created_at' => $comment->created_at,
                    'children' => $build($comment->id),
                ];
            })->all();
        };

        return $build(0);
    }
}
