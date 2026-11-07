<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Concerns\MakesBlogData;
use Tests\TestCase;

 
class ApiAccessTest extends TestCase
{
    use RefreshDatabase, MakesBlogData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeImgbb();
    }

    public function test_api_post_list_hides_drafts_and_private_posts(): void
    {
        $author = $this->makeUser();
        $this->makePost($author, ['title' => 'Api visible post']);
        $this->makePost($author, ['title' => 'Api draft post', 'is_published' => false]);
        $this->makePost($author, ['title' => 'Api private post', 'visibility' => 'private']);

        $content = $this->getJson('/api/v1/posts')->assertOk()->getContent();

        $this->assertStringContainsString('Api visible post', $content);
        $this->assertStringNotContainsString('Api draft post', $content);
        $this->assertStringNotContainsString('Api private post', $content);
    }

    public function test_api_returns_404_for_hidden_post_and_its_comments(): void
    {
        $author = $this->makeUser();
        $private = $this->makePost($author, ['visibility' => 'private']);
        $comment = $this->makeComment($private, $author, 'Api secret comment');

        $this->getJson("/api/v1/posts/{$private->id}")->assertNotFound();
        $this->getJson("/api/v1/comments/{$comment->id}")->assertNotFound();

        $list = $this->getJson('/api/v1/comments')->assertOk()->getContent();
        $this->assertStringNotContainsString('Api secret comment', $list);
    }

    public function test_api_comment_cannot_be_changed_or_deleted_by_a_stranger(): void
    {
        $owner = $this->makeUser();
        $stranger = $this->makeUser();
        $comment = $this->makeComment($this->makePost($owner), $owner, 'Original text');

        Sanctum::actingAs($stranger);

        $this->putJson("/api/v1/comments/{$comment->id}", ['text' => 'Hacked'])->assertForbidden();
        $this->deleteJson("/api/v1/comments/{$comment->id}")->assertForbidden();

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'text' => 'Original text']);
    }

    public function test_api_comment_owner_can_edit_and_delete(): void
    {
        $owner = $this->makeUser();
        $comment = $this->makeComment($this->makePost($owner), $owner, 'Before');

        Sanctum::actingAs($owner);

        $this->putJson("/api/v1/comments/{$comment->id}", ['text' => 'After'])->assertOk();
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'text' => 'After']);

        $this->deleteJson("/api/v1/comments/{$comment->id}")->assertNoContent();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_api_comment_cannot_be_moved_to_another_post(): void
    {
        $owner = $this->makeUser();
        $postA = $this->makePost($owner);
        $postB = $this->makePost($owner);
        $comment = $this->makeComment($postA, $owner);

        Sanctum::actingAs($owner);

        $this->putJson("/api/v1/comments/{$comment->id}", ['text' => 'Edited', 'post_id' => $postB->id])->assertOk();

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'post_id' => $postA->id]);
    }

    public function test_api_comment_gets_author_from_token_and_respects_post_visibility(): void
    {
        $author = $this->makeUser();
        $reader = $this->makeUser();
        $public = $this->makePost($author);
        $private = $this->makePost($author, ['visibility' => 'private']);

        Sanctum::actingAs($reader);

        $this->postJson('/api/v1/comments', ['post_id' => $public->id, 'text' => 'Hello'])->assertCreated();
        $this->assertDatabaseHas('comments', ['post_id' => $public->id, 'user_id' => $reader->id, 'text' => 'Hello']);

        $this->postJson('/api/v1/comments', ['post_id' => $private->id, 'text' => 'Sneaky'])->assertNotFound();
        $this->assertDatabaseMissing('comments', ['text' => 'Sneaky']);
    }

    public function test_api_post_cannot_be_changed_by_a_stranger(): void
    {
        $owner = $this->makeUser();
        $stranger = $this->makeUser();
        $post = $this->makePost($owner, ['title' => 'Api original']);

        Sanctum::actingAs($stranger);

        $this->putJson("/api/v1/posts/{$post->id}", ['title' => 'Hacked', 'content' => 'Hacked'])->assertForbidden();
        $this->deleteJson("/api/v1/posts/{$post->id}")->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Api original']);
    }

    public function test_api_login_returns_a_token_for_email_and_username(): void
    {
        $user = $this->makeUser(['username' => 'api_user']);

        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'username']]);

        $this->postJson('/api/v1/login', ['email' => 'api_user', 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token']);

        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertStatus(422);
    }
}
