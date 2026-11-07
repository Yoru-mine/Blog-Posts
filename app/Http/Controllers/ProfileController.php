<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Comment;
use App\Models\User;
use App\Services\ImgbbService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request, User $user): View
    {
        $viewer = $request->user();


        $visiblePosts = fn() => $user->posts()->published()->visibleTo($viewer);

        $isFollowing = $viewer
            ? $viewer->following()->where('users.id', $user->id)->exists()
            : false;

        return view('profile.show', [
            'viewer' => $viewer,
            'user' => $user,
            'postsCount' => $visiblePosts()->count(),
            'latestPost' => $visiblePosts()->latest()->first(),
            'recentPosts' => $visiblePosts()->latest()->take(6)->get(),
            'commentsReceived' => Comment::whereIn('post_id', $visiblePosts()->select('id'))->count(),
            'isFollowing' => $isFollowing,
        ]);
    }

    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'postsCount' => $user->posts()->count(),
            'latestPost' => $user->posts()->latest()->first(),
        ]);
    }

    public function update(ProfileUpdateRequest $request, ImgbbService $imgbb): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->except('avatar'));

        $oldAvatar = null;

        if ($request->hasFile('avatar')) {
            try {
                $newAvatar = $imgbb->upload($request->file('avatar'));
            } catch (\Throwable $e) {
                report($e);

                return Redirect::route('profile.edit')
                    ->withErrors(['avatar' => 'Failed to upload avatar. Please try again.']);
            }

            $oldAvatar = $user->avatar;
            $user->avatar = $newAvatar;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $profileChanged = $user->isDirty(['name', 'avatar']);

        $user->save();

        $this->deleteRemoteImage($imgbb, $oldAvatar);

        if ($profileChanged) {
            $this->forgetUserPostsCache($user);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroyAvatar(Request $request, ImgbbService $imgbb): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            $oldAvatar = $user->avatar;

            $user->avatar = null;
            $user->save();

            $this->deleteRemoteImage($imgbb, $oldAvatar);
            $this->forgetUserPostsCache($user);
        }

        return back()->with('status', 'avatar-deleted');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $postIds = $user->posts()->pluck('id');

        Auth::logout();

        $user->delete();

        $postIds->each(function ($postId) {
            Cache::forget("posts:show:{$postId}");
            Cache::forget("posts:similar:v2:{$postId}");
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function forgetUserPostsCache(User $user): void
    {
        $user->posts()->pluck('id')->each(function ($postId) {
            Cache::forget("posts:show:{$postId}");
        });
    }

    private function deleteRemoteImage(ImgbbService $imgbb, ?string $url): void
    {
        if (! $url) {
            return;
        }

        try {
            $imgbb->delete($url);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
