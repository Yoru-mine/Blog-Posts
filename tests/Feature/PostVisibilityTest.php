<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\MakesBlogData;
use Tests\TestCase;


class PostVisibilityTest extends TestCase
{
    use RefreshDatabase, MakesBlogData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeImgbb();
    }

    public function test_public_profile_does_not_leak_hidden_posts(): void
    {
        $author = $this->makeUser();
        $this->makePost($author, ['title' => 'Visible public post']);
        $this->makePost($author, ['title' => 'Hidden draft post', 'is_published' => false]);
        $this->makePost($author, ['title' => 'Hidden private post', 'visibility' => 'private']);
        $this->makePost($author, ['title' => 'Hidden friends post', 'visibility' => 'friends']);

        $this->get(route('users.show', $author))
            ->assertOk()
            ->assertSee('Visible public post')
            ->assertDontSee('Hidden draft post')
            ->assertDontSee('Hidden private post')
            ->assertDontSee('Hidden friends post');
    }

    public function test_feed_hides_drafts_and_non_public_posts_from_guests(): void
    {
        $author = $this->makeUser();
        $this->makePost($author, ['title' => 'Feed public post']);
        $this->makePost($author, ['title' => 'Feed draft post', 'is_published' => false]);
        $this->makePost($author, ['title' => 'Feed private post', 'visibility' => 'private']);
        $this->makePost($author, ['title' => 'Feed friends post', 'visibility' => 'friends']);

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSee('Feed public post')
            ->assertDontSee('Feed draft post')
            ->assertDontSee('Feed private post')
            ->assertDontSee('Feed friends post');
    }

    public function test_guest_gets_404_for_draft_and_private_posts(): void
    {
        $author = $this->makeUser();
        $draft = $this->makePost($author, ['is_published' => false]);
        $private = $this->makePost($author, ['visibility' => 'private']);

        $this->get(route('posts.show', $draft))->assertNotFound();
        $this->get(route('posts.show', $private))->assertNotFound();
    }

    public function test_stranger_gets_404_for_draft_and_private_posts(): void
    {
        $author = $this->makeUser();
        $stranger = $this->makeUser();
        $draft = $this->makePost($author, ['is_published' => false]);
        $private = $this->makePost($author, ['visibility' => 'private']);

        $this->actingAs($stranger)->get(route('posts.show', $draft))->assertNotFound();
        $this->actingAs($stranger)->get(route('posts.show', $private))->assertNotFound();
    }

    public function test_author_and_admin_can_open_hidden_posts(): void
    {
        $author = $this->makeUser();
        $admin = $this->makeAdmin();
        $draft = $this->makePost($author, ['is_published' => false]);
        $private = $this->makePost($author, ['visibility' => 'private']);

        $this->actingAs($author)->get(route('posts.show', $draft))->assertOk();
        $this->actingAs($author)->get(route('posts.show', $private))->assertOk();
        $this->actingAs($admin)->get(route('posts.show', $draft))->assertOk();
    }

    public function test_friends_only_post_is_visible_only_to_mutual_followers(): void
    {
        $author = $this->makeUser();
        $friend = $this->makeUser();
        $oneWayFollower = $this->makeUser();
        $stranger = $this->makeUser();

        $this->makeFriends($author, $friend);
        $oneWayFollower->following()->attach($author->id); 

        $post = $this->makePost($author, ['visibility' => 'friends']);

        $this->actingAs($friend)->get(route('posts.show', $post))->assertOk();
        $this->actingAs($oneWayFollower)->get(route('posts.show', $post))->assertNotFound();
        $this->actingAs($stranger)->get(route('posts.show', $post))->assertNotFound();
        $this->get(route('posts.show', $post))->assertNotFound();
    }

    public function test_search_respects_drafts_and_privacy(): void
    {
        $author = $this->makeUser();
        $this->makePost($author, ['title' => 'Secret public note']);
        $this->makePost($author, ['title' => 'Secret private plan', 'visibility' => 'private']);
        $this->makePost($author, ['title' => 'Secret draft idea', 'is_published' => false]);

        $titles = collect($this->getJson(route('posts.search', ['q' => 'Secret']))->assertOk()->json())
            ->pluck('title')
            ->all();

        $this->assertSame(['Secret public note'], $titles);
    }

    public function test_user_cannot_edit_or_delete_someone_elses_post(): void
    {
        $author = $this->makeUser();
        $stranger = $this->makeUser();
        $post = $this->makePost($author, ['title' => 'Original title']);

        $this->actingAs($stranger)->get(route('posts.edit', $post))->assertForbidden();

        $this->actingAs($stranger)->put(route('posts.update', $post), [
            'title' => 'Hacked',
            'content' => 'Hacked content',
            'visibility' => 'public',
            'category_id' => $post->category_id,
        ])->assertForbidden();

        $this->actingAs($stranger)->delete(route('posts.destroy', $post))->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Original title']);
    }

    public function test_user_cannot_touch_someone_elses_draft(): void
    {
        $author = $this->makeUser();
        $stranger = $this->makeUser();
        $draft = $this->makePost($author, ['is_published' => false, 'title' => 'My draft']);

        $this->actingAs($stranger)
            ->patchJson(route('posts.updateDraft', $draft->id), ['title' => 'Hacked'])
            ->assertNotFound();

        $this->actingAs($stranger)
            ->patch(route('posts.publish', $draft->id))
            ->assertNotFound();

        $this->assertDatabaseHas('posts', ['id' => $draft->id, 'title' => 'My draft', 'is_published' => false]);
    }

    public function test_post_creation_is_rate_limited(): void
    {
        $this->actingAs($this->makeUser());

        for ($i = 0; $i < 10; $i++) {
            $this->post(route('posts.store'), []);
        }

        $this->post(route('posts.store'), [])->assertStatus(429);
    }
}
