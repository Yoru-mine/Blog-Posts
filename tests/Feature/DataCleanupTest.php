<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\MakesBlogData;
use Tests\TestCase;


class DataCleanupTest extends TestCase
{
    use RefreshDatabase, MakesBlogData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeImgbb();
    }

    public function test_deleting_a_post_removes_comments_replies_reactions_and_likes(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $post = $this->makePost($author);
        $comment = $this->makeComment($post, $reader);
        $reply = $this->makeComment($post, $author, 'A reply', $comment->id);
        $comment->reactions()->create(['user_id' => $author->id, 'reaction' => 'like']);
        $post->likedByUsers()->attach($reader->id);

        $this->actingAs($author)->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
        $this->assertDatabaseCount('reactions', 0);
        $this->assertDatabaseMissing('post_like', ['post_id' => $post->id]);
    }

    public function test_deleting_a_comment_removes_its_replies_and_reactions(): void
    {
        $author = $this->makeUser();
        $post = $this->makePost($author);
        $comment = $this->makeComment($post, $author);
        $reply = $this->makeComment($post, $author, 'A reply', $comment->id);
        $reply->reactions()->create(['user_id' => $author->id, 'reaction' => 'heart']);

        $this->actingAs($author)->delete(route('comments.destroy', $comment))->assertRedirect();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseMissing('comments', ['id' => $reply->id]);
        $this->assertDatabaseCount('reactions', 0);
    }

    public function test_deleting_an_account_removes_its_posts_comments_and_follows(): void
    {
        $user = $this->makeUser();
        $other = $this->makeUser();
        $post = $this->makePost($user);
        $comment = $this->makeComment($this->makePost($other), $user);
        $this->makeFriends($user, $other);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
        $this->assertDatabaseCount('following', 0);
        $this->assertDatabaseHas('users', ['id' => $other->id]);
    }

    public function test_account_cannot_be_deleted_with_a_wrong_password(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->delete(route('profile.destroy'), ['password' => 'wrong'])
            ->assertSessionHasErrorsIn('userDeletion', 'password');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_follow_and_unfollow_work_and_self_follow_is_blocked(): void
    {
        $me = $this->makeUser();
        $other = $this->makeUser();

        $this->actingAs($me)->post(route('profile.show', $other))->assertRedirect();
        $this->assertDatabaseHas('following', ['follower_id' => $me->id, 'following_id' => $other->id]);

        $this->actingAs($me)->post(route('profile.show', $other));
        $this->assertDatabaseCount('following', 1);

        $this->actingAs($me)->delete(route('profile.show', $other))->assertRedirect();
        $this->assertDatabaseCount('following', 0);

        $this->actingAs($me)->post(route('profile.show', $me))->assertStatus(422);
    }
}
