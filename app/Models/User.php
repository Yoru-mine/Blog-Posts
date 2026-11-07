<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',

        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $user->posts()->each(fn(Post $post) => $post->delete());
            Comment::where('user_id', $user->id)->each(fn(Comment $comment) => $comment->delete());
            Reaction::where('user_id', $user->id)->delete();
            DB::table('post_like')->where('user_id', $user->id)->delete();
            DB::table('following')
                ->where('follower_id', $user->id)
                ->orWhere('following_id', $user->id)
                ->delete();
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
    public function following()
    {
        return $this->belongsToMany(User::class, 'following', 'follower_id', 'following_id');
    }
    public function drafts(): HasMany
    {
        return $this->hasMany(Post::class)
            ->where('is_published', false);
    }

    public function friendIds(): array
    {
        $iFollow = DB::table('following')->where('follower_id', $this->id)->pluck('following_id');
        $followMe = DB::table('following')->where('following_id', $this->id)->pluck('follower_id');

        return $iFollow->intersect($followMe)->map(fn($id) => (int) $id)->values()->all();
    }
}
