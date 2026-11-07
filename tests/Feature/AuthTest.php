<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\MakesBlogData;
use Tests\TestCase;


class AuthTest extends TestCase
{
    use RefreshDatabase, MakesBlogData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeImgbb();
    }

    public function test_user_can_log_in_with_email(): void
    {
        $user = $this->makeUser(['username' => 'alice_test']);

        $this->post('/login', ['login' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_log_in_with_username(): void
    {
        $user = $this->makeUser(['username' => 'alice_test']);

        $this->post('/login', ['login' => 'alice_test', 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser(['username' => 'alice_test']);

        $this->from('/login')
            ->post('/login', ['login' => 'alice_test', 'password' => 'not-the-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        $user = $this->makeUser(['username' => 'alice_test']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['login' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['login' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_registration_saves_username(): void
    {
        $this->post('/register', [
            'name' => 'New User',
            'username' => 'new_user',
            'email' => 'new@example.com',
            'password' => 'Str0ng-Pass-9421',
            'password_confirmation' => 'Str0ng-Pass-9421',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'username' => 'new_user']);
        $this->assertAuthenticated();
    }

    public function test_registration_rejects_duplicate_or_malformed_username(): void
    {
        $this->makeUser(['username' => 'taken_name']);

        $base = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'Str0ng-Pass-9421',
            'password_confirmation' => 'Str0ng-Pass-9421',
        ];

        $this->post('/register', $base + ['username' => 'taken_name'])->assertSessionHasErrors('username');
        $this->post('/register', $base + ['username' => 'bad name!'])->assertSessionHasErrors('username');
        $this->post('/register', $base + ['username' => 'ab'])->assertSessionHasErrors('username');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_profile_update_rejects_someone_elses_username(): void
    {
        $this->makeUser(['username' => 'taken_name']);
        $user = $this->makeUser(['username' => 'my_name']);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->patch(route('profile.update'), [
                'name' => 'Me',
                'username' => 'taken_name',
                'email' => $user->email,
            ])
            ->assertSessionHasErrors('username');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'my_name']);
    }

    public function test_profile_update_cannot_make_a_user_admin(): void
    {
        $user = $this->makeUser(['username' => 'my_name']);

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Me',
            'username' => 'my_name',
            'email' => $user->email,
            'is_admin' => 1,
        ]);

        $this->assertFalse((bool) $user->fresh()->is_admin);
    }

    public function test_admin_panel_is_closed_to_regular_users_and_guests(): void
    {
        $this->get(route('admin.panel'))->assertRedirect(route('login'));

        $this->actingAs($this->makeUser())->get(route('admin.panel'))->assertStatus(403);

        $this->actingAs($this->makeAdmin())->get(route('admin.panel'))->assertOk();
    }
}
