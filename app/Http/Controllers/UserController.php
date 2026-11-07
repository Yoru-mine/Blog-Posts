<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function searchUsers(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (mb_strlen($query) < 2 || mb_strlen($query) > 50) {
            return response()->json([]);
        }

        $cacheKey = 'users:search:v1:' . md5(mb_strtolower($query));

        $ids = Cache::remember($cacheKey, 300, function () use ($query) {
            $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $query);

            return User::where(function ($q) use ($escaped) {
                $q->where('name', 'LIKE', "%{$escaped}%")
                    ->orWhere('username', 'LIKE', "%{$escaped}%");
            })
                ->latest()
                ->take(30)
                ->pluck('id')
                ->all();
        });

        if (empty($ids)) {
            return response()->json([]);
        }

        $ids = array_slice($ids, 0, 6);
        $users = User::whereIn('id', $ids)->get(['id', 'name', 'username', 'avatar'])->keyBy('id');

        $results = collect($ids)
            ->map(fn($id) => $users->get($id))
            ->filter()
            ->map(fn($user) => [
                'id'     => $user->id,
                'name'   => $user->name,
                'username' => $user->username,
                'avatar' => $user->avatar ?: asset('images/default-avatar.svg'),
                'url'    => route('users.show', $user->id),
            ])
            ->values();

        return response()->json($results);
    }
}
