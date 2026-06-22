@extends('layouts.app')

@section('title', 'Post view')

@section('content')
    <style>
        .post-page {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            padding: 120px 24px 56px;
        }

        .post-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(26px);
            transform: scale(1.08);
            z-index: 0;
        }

        .post-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(10, 10, 10, 0.28);
        }

        .post-container {
            position: relative;
            z-index: 1;
            width: 1300px;
            margin: 0 auto;
            box-shadow: 0 42px 140px rgba(0, 0, 0, 0.62);
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .img-box {
            width: 100%;
            height: 560px;
            overflow: hidden;
            line-height: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .post-info-box {
            background-color: #2D2D2D;
            width: 100%;
            min-height: 500px;
            padding: 34px 50px 40px;
        }

        .post-info-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 22px;
        }

        .post-heading {
            flex: 1;
            min-width: 0;
        }

        .post-info-box h1 {
            font-size: 48px;
            line-height: 1.05;
            margin: 0;
        }

        .post-text {
            font-size: 22px;
            line-height: 1.6;
            max-width: 760px;
            margin: 0 0 28px;
            color: rgba(255, 255, 255, 0.9);
        }

        .post-author-card {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin: 0 0 24px;
            padding: 14px 16px;
            text-decoration: none;
            color: inherit;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .post-author-card:hover,
        .post-author-card:focus-visible {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.14);
            outline: none;
        }

        .post-author-avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid rgba(255, 255, 255, 0.12);
            flex-shrink: 0;
        }

        .post-author-copy {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .post-author-kicker {
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.52);
        }

        .post-author-name {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.94);
        }

        .post-author-hint {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.62);
        }

        .post-stats {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin: 0 0 26px;
        }

        .post-views,
        .post-likes-count {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
        }

        .like-form {
            margin-left: auto;
        }

        .like-button {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.08);
            color: #f6f0e6;
            font-family: inherit;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition:
                transform 0.18s ease,
                background 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                opacity 0.2s ease;
        }

        .like-button:hover,
        .like-button:focus-visible {
            transform: translateY(-1px) scale(1.03);
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
            outline: none;
        }

        .like-button:active {
            transform: scale(0.94);
        }

        .like-button.is-liked {
            background: rgba(255, 214, 224, 0.92);
            color: #b43f63;
            border-color: transparent;
            box-shadow: 0 10px 26px rgba(180, 63, 99, 0.22);
        }

        .like-button.is-loading {
            pointer-events: none;
            opacity: 0.72;
        }

        .like-button::after {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 221, 229, 0.55) 0%, rgba(255, 221, 229, 0) 72%);
            opacity: 0;
            transform: scale(0.45);
            pointer-events: none;
        }

        .like-button.is-liked.is-animating::after {
            animation: like-ring 0.55s ease-out;
        }

        .like-icon {
            width: 28px;
            height: 28px;
            display: block;
            position: relative;
            z-index: 1;
            transition: transform 0.18s ease, filter 0.2s ease;
        }

        .like-icon path {
            fill: none;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: fill 0.22s ease, stroke 0.22s ease;
        }

        .like-button:hover .like-icon,
        .like-button:focus-visible .like-icon {
            transform: scale(1.08);
        }

        .like-button.is-liked .like-icon path {
            fill: currentColor;
            stroke: currentColor;
        }

        .like-button.is-liked .like-icon {
            filter: drop-shadow(0 6px 12px rgba(180, 63, 99, 0.25));
        }

        .like-button.is-animating .like-icon {
            animation: like-pop 0.52s cubic-bezier(.2, .9, .22, 1.2);
        }

        .post-info-box h4 {
            font-size: 32px;
            margin: 0 0 18px;
        }

        .text-muted {
            font-size: 20px;
            margin: 0 0 22px;
            color: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            padding: 20px 22px;
        }

        .comments-list {
            display: grid;
            gap: 14px;
            margin: 0 0 28px;
        }

        .comment-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px 20px;
            overflow: hidden;
            max-height: 240px;
            transition:
                opacity 0.28s ease,
                transform 0.28s ease,
                max-height 0.3s ease,
                margin 0.3s ease,
                padding 0.3s ease,
                border-width 0.3s ease;
        }

        .comment-card.is-removing {
            opacity: 0;
            transform: translateY(-10px);
            max-height: 0;
            margin: 0;
            padding-top: 0;
            padding-bottom: 0;
            border-width: 0;
        }

        .comment-top {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .comment-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .comment-body {
            min-width: 0;
            flex: 1;
        }

        .comment-author {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.76);
            margin: 0 0 10px;
        }

        .comment-author-link {
            color: inherit;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .comment-author-link:hover,
        .comment-author-link:focus-visible {
            opacity: 0.86;
            text-decoration: underline;
            outline: none;
        }

        .comment-text {
            font-size: 18px;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.94);
            margin: 0 0 10px;
        }

        .comment-time {
            display: block;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.48);
        }

        .comment-actions {
            margin-top: 14px;
        }

        .comment-delete-button {
            min-width: 96px;
            height: 36px;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 214, 214, 0.92);
            color: #3a1b1b;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .comment-delete-button:hover,
        .comment-delete-button:focus-visible {
            transform: translateY(-1px);
            opacity: 0.95;
            outline: none;
        }

        .comment-delete-button.is-loading {
            pointer-events: none;
            opacity: 0.72;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 18px;
        }

        #comment-text {
            width: 980px;
            height: 66px;
            background: #1F1F1F;
            border: 1px solid #6f6f6f;
            color: white;
            padding: 0 20px;
            font-family: inherit;
            font-size: 16px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        #comment-text::placeholder {
            color: #9A9A9A;
            font-size: 16px;
        }

        #comment-text:hover {
            border-color: rgba(255, 255, 255, 0.42);
        }

        #comment-text:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.72);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.08);
            background: #242424;
        }

        .comment-form button {
            width: 128px;
            height: 46px;
            background: #f2ede2;
            color: #1E1E1E;
            font-size: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease, box-shadow 0.2s ease;
        }

        .comment-form button:hover,
        .comment-form button:focus-visible {
            transform: translateY(-1px);
            opacity: 0.95;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
            outline: none;
        }

        @keyframes like-pop {
            0% {
                transform: scale(0.82);
            }

            45% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes like-ring {
            0% {
                opacity: 0.55;
                transform: scale(0.45);
            }

            100% {
                opacity: 0;
                transform: scale(1.35);
            }
        }

        @media (max-width: 1360px) {
            .post-container {
                width: min(1300px, 100%);
            }

            .post-info-box {
                padding: 28px 28px 36px;
            }

            #comment-text {
                width: 100%;
            }
        }

        @media (max-width: 900px) {
            .post-page {
                padding: 104px 14px 28px;
            }

            .post-container {
                width: 100%;
            }

            .img-box {
                height: 300px;
            }

            .post-info-box {
                min-height: auto;
                padding: 22px 18px 28px;
            }

            .post-info-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
                margin-bottom: 18px;
            }

            .post-info-box h1 {
                font-size: 34px;
            }

            .post-text {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .post-info-box h4 {
                font-size: 26px;
                margin-bottom: 14px;
            }

            .comment-text {
                font-size: 16px;
            }

            .comment-top {
                gap: 12px;
            }

            .comment-avatar {
                width: 46px;
                height: 46px;
            }

            .like-form {
                margin-left: 0;
            }

            .like-button,
            .comment-form button {
                width: 100%;
            }
        }
    </style>

    @php
        $imageUrl = $post->image ? asset('storage/' . $post->image) : asset('images/default.png');
        $postAuthorAvatarUrl = $post->user?->avatar
            ? asset('storage/' . $post->user->avatar)
            : asset('images/default-avatar.svg');
    @endphp

    <div class="post-page">
        <div class="post-bg" style="background-image: url('{{ $imageUrl }}');"></div>

        <div class="post-container" role="main">
            <div class="img-box">
                <img src="{{ $imageUrl }}" alt="Post image" class="post-image" loading="lazy">
            </div>

            <div class="post-info-box">
                <div class="post-info-header">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                    </div>

                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-like-form>
                        @csrf
                        <button
                            type="submit"
                            class="like-button {{ $isLiked ? 'is-liked' : '' }}"
                            aria-label="{{ $isLiked ? 'Unlike post' : 'Like post' }}"
                            aria-pressed="{{ $isLiked ? 'true' : 'false' }}"
                            data-like-button
                        >
                            <svg class="like-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 20.4 4.9 13.8A4.85 4.85 0 0 1 11.8 7l.2.24.2-.24a4.85 4.85 0 0 1 6.9 6.8Z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <p class="post-text">{{ $post->content }}</p>
                @if ($post->user)
                    <a href="{{ route('users.show', $post->user) }}" class="post-author-card" aria-label="Open author profile">
                        <div class="post-author-avatar" style="background-image: url('{{ $postAuthorAvatarUrl }}');"></div>

                        <div class="post-author-copy">
                            <span class="post-author-kicker">Author</span>
                            <span class="post-author-name">{{ $post->user->name }}</span>
                            <span class="post-author-hint">Open profile</span>
                        </div>
                    </a>
                @endif
                <div class="post-stats">
                    <p class="post-views"><strong>Views:</strong> {{ $views }}</p>
                    <p class="post-likes-count"><strong>Likes:</strong> <span data-like-count>{{ $likes }}</span></p>
                </div>
                <h4>Comments:</h4>

                @if ($post->comments->isEmpty())
                    <p class="text-muted" style="user-select: none;" data-comments-empty>No comments yet.</p>
                @else
                    <div class="comments-list">
                        @foreach ($post->comments as $comment)
                            @php
                                $commentAvatarUrl = $comment->user?->avatar
                                    ? asset('storage/' . $comment->user->avatar)
                                    : asset('images/default-avatar.svg');
                            @endphp
                            <div
                                id="comment-{{ $comment->id }}"
                                class="comment-card"
                                role="article"
                                aria-label="Comment by {{ $comment->author }}"
                            >
                                <div class="comment-top">
                                    <div class="comment-avatar" style="background-image: url('{{ $commentAvatarUrl }}');"></div>

                                    <div class="comment-body">
                                        <p class="comment-author">
                                            @if ($comment->user)
                                                <a href="{{ route('users.show', $comment->user) }}" class="comment-author-link">
                                                    {{ $comment->author }}
                                                </a>
                                            @else
                                                {{ $comment->author }}
                                            @endif
                                        </p>
                                        <p class="comment-text">{{ $comment->text }}</p>
                                        <small class="comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>

                                @if (auth()->id() === $comment->user_id || auth()->user()?->isAdmin())
                                    <div class="comment-actions">
                                        <form
                                            action="{{ route('comments.destroy', $comment->id) }}"
                                            method="POST"
                                            data-comment-delete-form
                                            data-confirm
                                            data-confirm-ajax="true"
                                            data-confirm-title="Delete comment?"
                                            data-confirm-message="This comment will be removed permanently from the post."
                                            data-confirm-button="Delete comment"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="comment-delete-button" data-comment-delete-button>Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="comment-form" novalidate>
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="text" name="text" id="comment-text" placeholder="Write a comment..." aria-label="Comment text" required>
                    <button type="submit" aria-label="Send comment">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likeForm = document.querySelector('[data-like-form]');
            const likeButton = likeForm?.querySelector('[data-like-button]');
            const likeCount = document.querySelector('[data-like-count]');
            let commentsList = document.querySelector('.comments-list');

            if (likeForm && likeButton && likeCount) {
                likeForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (likeButton.classList.contains('is-loading')) {
                        return;
                    }

                    likeButton.classList.add('is-loading');

                    try {
                        const response = await fetch(likeForm.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(likeForm),
                            credentials: 'same-origin',
                        });

                        if (!response.ok) {
                            throw new Error('Like request failed.');
                        }

                        const data = await response.json();
                        likeButton.classList.toggle('is-liked', Boolean(data.liked));
                        likeButton.classList.add('is-animating');
                        likeButton.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
                        likeButton.setAttribute('aria-label', data.liked ? 'Unlike post' : 'Like post');
                        likeCount.textContent = data.likes;

                        window.setTimeout(() => {
                            likeButton.classList.remove('is-animating');
                        }, 520);
                    } catch (error) {
                        window.location.href = '{{ route('login') }}';
                    } finally {
                        likeButton.classList.remove('is-loading');
                    }
                });
            }

            document.querySelectorAll('[data-comment-delete-form]').forEach((form) => {
                const runDelete = async () => {
                    const button = form.querySelector('[data-comment-delete-button]');
                    const commentCard = form.closest('.comment-card');

                    if (!button || !commentCard || button.classList.contains('is-loading')) {
                        return;
                    }

                    button.classList.add('is-loading');

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(form),
                            credentials: 'same-origin',
                        });

                        if (!response.ok) {
                            throw new Error('Delete request failed.');
                        }

                        commentCard.classList.add('is-removing');

                        window.setTimeout(() => {
                            commentCard.remove();

                            if (commentsList && !commentsList.querySelector('.comment-card')) {
                                commentsList.remove();
                                commentsList = null;

                                if (!document.querySelector('[data-comments-empty]')) {
                                    const emptyState = document.createElement('p');
                                    emptyState.className = 'text-muted';
                                    emptyState.dataset.commentsEmpty = 'true';
                                    emptyState.style.userSelect = 'none';
                                    emptyState.textContent = 'No comments yet.';
                                    const commentsTitle = document.querySelector('.post-info-box h4');
                                    commentsTitle?.insertAdjacentElement('afterend', emptyState);
                                }
                            }
                        }, 280);
                    } catch (error) {
                        button.classList.remove('is-loading');
                    }
                };

                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                });

                form.addEventListener('confirmed-submit', runDelete);
            });
        });
    </script>
@endsection
