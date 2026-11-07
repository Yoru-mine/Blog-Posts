<?php

namespace Tests\Feature\Concerns;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\ImgbbService;


trait MakesBlogData
{
    protected function fakeImgbb(): void
    {
        $this->mock(ImgbbService::class);
    }

    protected function makeUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function makeAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    protected function makePost(User $author, array $overrides = []): Post
    {
        $category = Category::firstOrCreate(['slug' => 'test'], ['name' => 'Test']);

        return Post::create(array_merge([
            'user_id' => $author->id,
            'title' => 'Post ' . uniqid(),
            'content' => 'Some content',
            'visibility' => 'public',
            'category_id' => $category->id,
            'is_published' => true,
        ], $overrides));
    }

    protected function makeComment(Post $post, User $author, string $text = 'A comment', ?int $parentId = null): Comment
    {
        return Comment::create([
            'post_id' => $post->id,
            'user_id' => $author->id,
            'parent_id' => $parentId,
            'author' => $author->name,
            'text' => $text,
        ]);
    }

    protected function makeFriends(User $a, User $b): void
    {
        $a->following()->attach($b->id);
        $b->following()->attach($a->id);
    }
}
