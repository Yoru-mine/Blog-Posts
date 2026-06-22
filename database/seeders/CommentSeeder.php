<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'admin@example.com',
            'alice@example.com',
            'mia@example.com',
            'noah@example.com',
            'ethan@example.com',
        ])->get()->keyBy('email');

        $posts = Post::all()->keyBy('title');

        $comments = [
            [
                'post_title' => 'Playing Minecraft',
                'email' => 'alice@example.com',
                'text' => 'The atmosphere in this one is really strong. It feels calm and a little mysterious.',
            ],
            [
                'post_title' => 'Playing Minecraft',
                'email' => 'mia@example.com',
                'text' => 'I like how the visual mood of the post matches the screenshot.',
            ],
            [
                'post_title' => 'Healthy eating',
                'email' => 'noah@example.com',
                'text' => 'Simple but nice post. It feels clean and honest.',
            ],
            [
                'post_title' => 'A walk in the park',
                'email' => 'admin@example.com',
                'text' => 'This one would look great with a colder image palette too.',
            ],
        ];

        foreach ($comments as $comment) {
            $user = $users[$comment['email']] ?? null;
            $post = $posts[$comment['post_title']] ?? null;

            if (! $user || ! $post) {
                continue;
            }

            Comment::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'text' => $comment['text'],
                ],
                [
                    'author' => $user->name,
                ]
            );
        }
    }
}
