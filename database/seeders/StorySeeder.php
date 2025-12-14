<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', User::ROLE_MEMBER)->get();

        // Create 3 stories per member
        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $story = Story::create([
                    'user_id' => $user->id,
                    'title' => "Story by {$user->name} - Part $i",
                    'body' => "This is a story written by {$user->name}. " .
                        "It contains interesting content about their life and experiences.\n\n" .
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. " .
                        "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                    'is_published' => true,
                ]);

                // Add 2-4 root comments per story
                for ($j = 1; $j <= rand(2, 4); $j++) {
                    $commenter = $users->random();
                    $comment = Comment::create([
                        'story_id' => $story->id,
                        'user_id' => $commenter->id,
                        'body' => "Great story! {$commenter->name} here. This is a comment.",
                        'depth' => 0,
                    ]);

                    // Add 1-2 replies per root comment
                    for ($k = 1; $k <= rand(1, 2); $k++) {
                        $replier = $users->random();
                        Comment::create([
                            'story_id' => $story->id,
                            'user_id' => $replier->id,
                            'parent_id' => $comment->id,
                            'body' => "I agree! {$replier->name} here, replying to the comment.",
                            'depth' => 1,
                        ]);
                    }
                }

                // Add 3-6 likes per story
                for ($m = 1; $m <= rand(3, 6); $m++) {
                    $liker = $users->random();
                    Like::firstOrCreate(
                        ['story_id' => $story->id, 'user_id' => $liker->id],
                        ['story_id' => $story->id, 'user_id' => $liker->id]
                    );
                }

                // Update counts
                $story->update([
                    'comments_count' => $story->allComments()->count(),
                    'likes_count' => $story->likes()->count(),
                ]);
            }
        }
    }
}
