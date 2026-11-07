<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\MakesBlogData;
use Tests\TestCase;


class CommentAccessTest extends TestCase
{
    use RefreshDatabase, MakesBlogData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeImgbb();
    }

    public function test_user_can_comment_on_a_public_post(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $post = $this->makePost($author);

        $this->actingAs($reader)
            ->post(route('comments.store', $post), ['post_id' => $post->id, 'text' => 'Nice post!'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $reader->id,
            'text' => 'Nice post!',
        ]);
    }

    public function test_user_cannot_comment_on_private_or_draft_posts_of_others(): void
    {
        $author = $this->makeUser();
        $stranger = $this->makeUser();
        $private = $this->makePost($author, ['visibility' => 'private']);
        $draft = $this->makePost($author, ['is_published' => false]);

        foreach ([$private, $draft] as $post) {
            $this->actingAs($stranger)
                ->post(route('comments.store', $post), ['post_id' => $post->id, 'text' => 'Sneaky'])
                ->assertNotFound();
        }

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_reply_must_belong_to_the_same_post(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $postA = $this->makePost($author);
        $postB = $this->makePost($author);
        $commentOnB = $this->makeComment($postB, $author);

        $this->actingAs($reader)
            ->post(route('comments.store', $postA), [
                'post_id' => $postA->id,
                'text' => 'Cross-post reply',
                'parent_id' => $commentOnB->id,
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('comments', ['text' => 'Cross-post reply']);
    }

    public function test_only_author_or_admin_can_delete_a_comment(): void
    {
        $postAuthor = $this->makeUser();
        $commenter = $this->makeUser();
        $stranger = $this->makeUser();
        $admin = $this->makeAdmin();
        $post = $this->makePost($postAuthor);
        $comment = $this->makeComment($post, $commenter);

        $this->actingAs($stranger)->delete(route('comments.destroy', $comment))->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);

        $this->actingAs($admin)->delete(route('comments.destroy', $comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_reactions_are_saved_and_toggled_off_on_second_click(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $comment = $this->makeComment($this->makePost($author), $author);

        $this->actingAs($reader)
            ->postJson(route('comments.reaction.toggle', $comment), ['reaction' => 'like'])
            ->assertOk()
            ->assertJson(['action' => 'added']);

        $this->assertDatabaseHas('reactions', ['user_id' => $reader->id, 'reactable_id' => $comment->id, 'reaction' => 'like']);

        $this->actingAs($reader)
            ->postJson(route('comments.reaction.toggle', $comment), ['reaction' => 'like'])
            ->assertOk()
            ->assertJson(['action' => 'deleted']);

        $this->assertDatabaseCount('reactions', 0);
    }

    public function test_user_cannot_react_to_comments_under_hidden_posts(): void
    {
        $author = $this->makeUser();
        $stranger = $this->makeUser();
        $private = $this->makePost($author, ['visibility' => 'private']);
        $comment = $this->makeComment($private, $author);

        $this->actingAs($stranger)
            ->postJson(route('comments.reaction.toggle', $comment), ['reaction' => 'like'])
            ->assertNotFound();

        $this->assertDatabaseCount('reactions', 0);
    }

    public function test_commenting_is_rate_limited(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $post = $this->makePost($author);

        $this->actingAs($reader);

        for ($i = 0; $i < 20; $i++) {
            $this->post(route('comments.store', $post), ['post_id' => $post->id, 'text' => "Comment {$i}"]);
        }

        $this->post(route('comments.store', $post), ['post_id' => $post->id, 'text' => 'One too many'])
            ->assertStatus(429);
    }
}
