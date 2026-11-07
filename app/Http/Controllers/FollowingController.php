<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowingController extends Controller
{
    public function store(User $user){
        $currentUser = Auth::user();

        abort_if($currentUser->id === $user->id, 422, 'You cannot follow yourself.');

        $currentUser->following()->syncWithoutDetaching([$user->id]);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        $currentUser->following()->detach($user->id);

        return redirect()->back();
    }
}
