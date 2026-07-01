<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request, \App\Models\User $user): View
    {
        $recentPosts = $user->posts()->latest()->take(6)->get();

        return view('profile.show', [
            'viewer' => $request->user(),
            'user' => $user,
            'postsCount' => $user->posts()->count(),
            'latestPost' => $user->posts()->latest()->first(),
            'recentPosts' => $recentPosts,
            'commentsReceived' => $user->posts()->withCount('comments')->get()->sum('comments_count'),
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


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->hasFile('avatar')) {
            $path = $request->file('image')->store('', 's3');
            $data['image'] = $path;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        \Illuminate\Support\Facades\Cache::flush();
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
