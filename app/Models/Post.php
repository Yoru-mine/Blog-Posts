<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'views',
        'visibility',
        'category_id',
        'is_published'
    ];
    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Post $post) {
            $post->comments()->each(fn(Comment $comment) => $comment->delete());
            $post->tags()->detach();
            $post->likedByUsers()->detach();
        });
    }


    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }


    public function scopeVisibleTo(Builder $query, ?User $viewer): Builder
    {
        if ($viewer && $viewer->isAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($viewer) {
            $q->where('visibility', 'public')->orWhereNull('visibility');

            if ($viewer) {
                $q->orWhere('user_id', $viewer->id);

                $friendIds = $viewer->friendIds();

                if (! empty($friendIds)) {
                    $q->orWhere(function (Builder $friends) use ($friendIds) {
                        $friends->where('visibility', 'friends')
                            ->whereIn('user_id', $friendIds);
                    });
                }
            }
        });
    }

    /** Может ли $viewer открыть этот пост (с учётом черновиков). */
    public function isVisibleTo(?User $viewer): bool
    {
        $isOwnerOrAdmin = $viewer && ($viewer->id === $this->user_id || $viewer->isAdmin());

        if ($this->is_published !== null && ! $this->is_published) {
            return (bool) $isOwnerOrAdmin;
        }

        $visibility = $this->visibility ?? 'public';

        if ($visibility === 'public') {
            return true;
        }

        if (! $viewer) {
            return false;
        }

        if ($isOwnerOrAdmin) {
            return true;
        }

        return $visibility === 'friends'
            && in_array((int) $this->user_id, $viewer->friendIds(), true);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_like')->withTimestamps();
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }
    public function createDraft(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id')->where('is_published', false);
    }
}
