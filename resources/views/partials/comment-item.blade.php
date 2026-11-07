@php
    $replies = $repliesMap->get($comment->id, collect());
    $commentAvatarUrl = $comment->user?->avatar ?: asset('images/default-avatar.svg');
    $commentAuthorName = $comment->user?->name ?? $comment->author;

    // Глубже этого уровня отступ у ответов больше не растёт
    $isFlat = $depth >= 3;
@endphp

<div class="comment-thread" data-depth="{{ $depth }}">
    <div id="comment-{{ $comment->id }}" class="comment-card" role="article"
        aria-label="Comment by {{ $commentAuthorName }}">
        <div class="comment-top">
            <div class="comment-avatar" style="background-image: url('{{ $commentAvatarUrl }}');"></div>

            <div class="comment-body">
                <p class="comment-author">
                    @if ($comment->user)
                        <a href="{{ route('users.show', $comment->user) }}" class="comment-author-link">
                            {{ $commentAuthorName }}
                        </a>
                    @else
                        {{ $commentAuthorName }}
                    @endif
                </p>

                @if ($parentName && $depth >= 4)
                    <p class="comment-reply-to">↳ {{ $parentName }}</p>
                @endif

                <p class="comment-text">{{ $comment->text }}</p>
                <small class="comment-time">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        </div>

        <div class="comment-actions">
            @auth
                <button type="button" class="comment-reply-button" data-reply-toggle aria-expanded="false">Reply</button>
            @else
                <a href="{{ route('login') }}" class="comment-reply-button">Reply</a>
            @endauth

            @if (auth()->id() === $comment->user_id || auth()->user()?->isAdmin())
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" data-comment-delete-form
                    data-confirm data-confirm-ajax="true" data-confirm-title="Delete comment?"
                    data-confirm-message="This comment will be removed permanently from the post."
                    data-confirm-button="Delete comment">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="comment-delete-button" data-comment-delete-button>Delete</button>
                </form>
            @endif
        </div>

        @include('components.comment-reactions', ['comment' => $comment])
    </div>

    @auth
        <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="reply-form"
            data-reply-form novalidate>
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <input type="text" name="text" class="reply-input" placeholder="Write a reply..." maxlength="1000"
                aria-label="Reply text" required>
            <div class="reply-form-actions">
                <button type="submit" class="reply-send">Send</button>
                <button type="button" class="reply-cancel" data-reply-cancel>Cancel</button>
            </div>
        </form>
    @endauth

    @if ($replies->isNotEmpty())
        <div class="comment-replies {{ $isFlat ? 'is-flat' : '' }}">
            @foreach ($replies as $reply)
                @include('partials.comment-item', [
                    'comment' => $reply,
                    'post' => $post,
                    'repliesMap' => $repliesMap,
                    'depth' => $depth + 1,
                    'parentName' => $commentAuthorName,
                ])
            @endforeach
        </div>
    @endif
</div>
