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
            filter: blur(26px) saturate(0.7);
            transform: scale(1.08);
            z-index: 0;
        }

        .post-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--overlay-bg);
        }

        .post-container {
            position: relative;
            z-index: 1;
            width: 1300px;
            margin: 0 auto;
            box-shadow: 0 42px 140px rgba(0, 0, 0, 0.7);
        }

        .img-box {
            width: 100%;
            height: 560px;
            overflow: hidden;
            line-height: 0;
            background: var(--surface);
        }

        .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(0.85);
        }

        .post-info-box {
            background-color: var(--surface-2);
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
            text-shadow: var(--glow);
        }

        .post-date {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
            font-size: 15px;
            color: var(--text-muted);
        }

        .post-date svg {
            flex-shrink: 0;
            opacity: 0.85;
        }

        .post-date-relative {
            color: var(--text-muted);
        }

        .post-draft-badge {
            color: var(--accent-contrast);
            background: var(--accent);
        }

        .post-category {
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            text-shadow: var(--glow);
            transition: text-shadow 0.2s ease, color 0.2s ease;
        }

        .post-category:hover,
        .post-category:focus-visible {
            text-shadow: var(--glow-hover);
        }

        .post-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .post-tag {
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 0 14px;
            font-size: 15px;
            color: var(--text-main);
            background: var(--surface-soft);
            text-decoration: none;
            transition: background-color 0.2s ease, text-shadow 0.2s ease;
        }

        .post-tag:hover,
        .post-tag:focus-visible {
            background: var(--surface-hover);
            text-shadow: var(--glow);
        }

        .post-visibility-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 14px 0 0 14px;
            padding: 4px 12px;
            font-size: 15px;
            color: var(--text-main);
            background: var(--surface-soft);
            vertical-align: top;
        }

        .post-visibility-badge svg {
            width: 16px;
            height: 16px;
        }

        .post-text {
            font-size: 22px;
            line-height: 1.6;
            max-width: 760px;
            margin: 0 0 28px;
            color: var(--text-main);
        }

        .post-author-card {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin: 0 0 24px;
            padding: 14px 18px;
            text-decoration: none;
            color: inherit;
            background: var(--surface-soft);
            transition: background-color 0.2s ease;
        }

        .post-author-card:hover,
        .post-author-card:focus-visible {
            background: var(--surface-hover);
        }

        .post-author-avatar {
            width: 58px;
            height: 58px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            flex-shrink: 0;
        }

        .post-author-copy {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .post-author-kicker {
            font-size: 14px;
            color: var(--text-muted);
        }

        .post-author-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-main);
            text-shadow: var(--glow);
        }

        .post-author-hint {
            font-size: 15px;
            color: var(--text-muted);
        }

        .post-stats {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
            margin: 0 0 26px;
        }

        .post-views,
        .post-likes-count {
            font-size: 16px;
            color: var(--text-muted);
        }

        .post-views [data-view-count],
        .post-likes-count [data-like-count] {
            font-weight: 700;
            color: var(--accent);
            text-shadow: var(--glow);
        }

        .like-form {
            margin-left: auto;
        }

        /* ---- Иконочные кнопки: лайк и «поделиться» ---- */
        .like-button,
        .share-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: var(--btn-h);
            min-height: var(--btn-h);
            padding: 8px;
            background: transparent;
            border: 0;
            color: var(--text-main);
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, opacity 0.2s ease;
        }

        .like-button:hover,
        .like-button:focus-visible,
        .share-button:hover,
        .share-button:focus-visible {
            background: var(--surface-hover);
        }

        .like-button.is-liked {
            color: var(--danger);
        }

        .like-button.is-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .like-icon {
            width: 28px;
            height: 28px;
            display: block;
            position: relative;
            z-index: 1;
            transition: filter 0.2s ease;
        }

        .like-icon path {
            fill: none;
            stroke: currentColor;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: fill 0.22s ease, stroke 0.22s ease;
        }

        .like-button.is-liked .like-icon path {
            fill: currentColor;
            stroke: currentColor;
        }

        .like-button.is-liked .like-icon {
            filter: drop-shadow(0 0 8px rgba(var(--danger-rgb), 0.4));
        }

        .like-button.is-animating .like-icon {
            animation: like-pop 0.52s cubic-bezier(.2, .9, .22, 1.2);
        }

        .share-icon {
            display: block;
            width: 28px;
            height: 28px;
            color: var(--text-main);
        }

        .post-info-box h4 {
            font-size: 32px;
            margin: 0 0 18px;
            text-shadow: var(--glow);
        }

        .text-muted {
            font-size: 18px;
            margin: 0 0 22px;
            color: var(--text-muted);
            background: var(--surface-soft);
            padding: 20px 22px;
        }

        /* ---- Комментарии ---- */
        .comments-list {
            display: grid;
            gap: 8px;
            margin: 0 0 20px;
        }

        .comment-card {
            background: var(--surface-3);
            padding: 12px 16px;
            overflow: hidden;
            max-height: 240px;
            transition:
                opacity 0.28s ease,
                transform 0.28s ease,
                max-height 0.3s ease,
                margin 0.3s ease,
                padding 0.3s ease;
        }

        .comment-card.is-removing {
            opacity: 0;
            transform: translateY(-10px);
            max-height: 0;
            margin: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        .comment-top {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            flex-shrink: 0;
        }

        .comment-body {
            min-width: 0;
            flex: 1;
        }

        .comment-author {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
            margin: 0 0 3px;
        }

        .comment-author-link {
            color: inherit;
            text-decoration: none;
            text-shadow: var(--glow-faint);
            transition: text-shadow 0.2s ease;
        }

        .comment-author-link:hover,
        .comment-author-link:focus-visible {
            text-shadow: var(--glow-hover);
            text-decoration: underline;
        }

        .comment-text {
            font-size: 16px;
            line-height: 1.5;
            color: var(--text-main);
            margin: 0 0 4px;
        }

        .comment-time {
            display: block;
            font-size: 14px;
            color: var(--text-muted);
        }

        .comment-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        /* опасное действие уезжает вправо, отдельно от «Reply» */
        .comment-actions form {
            margin: 0 0 0 auto;
        }

        /* ---- Единая база кнопок страницы ---- */
        .comment-reply-button,
        .comment-delete-button,
        .reply-send,
        .reply-cancel,
        .comment-form button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 88px;
            min-height: var(--btn-h);
            padding: var(--btn-pad);
            border: 0;
            background: var(--surface-soft);
            color: var(--text-main);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, opacity 0.2s ease;
        }

        .comment-reply-button:hover,
        .comment-reply-button:focus-visible,
        .comment-reply-button[aria-expanded="true"],
        .reply-cancel:hover,
        .reply-cancel:focus-visible {
            background: var(--surface-hover);
        }

        /* главное действие страницы: отправка комментария */
        .reply-send,
        .comment-form button {
            background: var(--accent);
            color: var(--accent-contrast);
            font-weight: 700;
        }

        .reply-send:hover,
        .reply-send:focus-visible,
        .comment-form button:hover,
        .comment-form button:focus-visible {
            background: var(--accent-hover);
        }

        .reply-send:disabled,
        .comment-form button:disabled {
            opacity: 0.6;
            pointer-events: none;
        }

        .comment-delete-button {
            background: rgba(var(--danger-rgb), 0.14);
            color: var(--danger);
        }

        .comment-delete-button:hover,
        .comment-delete-button:focus-visible {
            background: rgba(var(--danger-rgb), 0.26);
        }

        .comment-delete-button.is-loading {
            pointer-events: none;
            opacity: 0.72;
        }

        /* ---- Ветка комментария и ответы ---- */
        .comment-thread {
            display: grid;
            gap: 8px;
            transition: opacity 0.28s ease;
        }

        .comment-thread.is-removing {
            opacity: 0;
        }

        /* вложенность читается по отступу и более тёмному фону карточек */
        .comment-replies {
            display: grid;
            gap: 8px;
            margin-left: 22px;
            padding-left: 12px;
        }

        /* Глубже 4 уровней отступ больше не растёт */
        .comment-replies.is-flat {
            margin-left: 0;
            padding-left: 0;
        }

        .comment-replies .comment-card {
            background: var(--surface);
            padding: 10px 14px;
        }

        .comment-replies .comment-avatar {
            width: 34px;
            height: 34px;
        }

        .comment-replies .comment-text {
            font-size: 15px;
        }

        .comment-reply-to {
            margin: 0 0 3px;
            font-size: 14px;
            color: var(--text-muted);
        }

        /* ---- Поля ввода: фокус светом и фоном, без рамок ---- */
        .reply-form {
            display: none;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-left: 22px;
        }

        .reply-form.is-open {
            display: flex;
        }

        .reply-input,
        #comment-text {
            background: var(--surface-3);
            border: 0;
            color: var(--text-main);
            font-family: inherit;
            box-sizing: border-box;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .reply-input {
            width: min(640px, 100%);
            min-height: var(--btn-h);
            padding: 0 16px;
            font-size: 15px;
        }

        #comment-text {
            width: 980px;
            min-height: 48px;
            padding: 0 20px;
            font-size: 16px;
        }

        .reply-input::placeholder,
        #comment-text::placeholder {
            color: var(--text-muted);
            font-size: 15px;
        }

        .reply-input:hover,
        #comment-text:hover {
            background: var(--surface-hover);
        }

        .reply-input:focus,
        #comment-text:focus {
            outline: none;
            background: var(--surface-hover);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 24px rgba(var(--accent-rgb), 0.22);
        }

        .reply-form-actions {
            display: flex;
            gap: 8px;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .comment-form button {
            min-width: 120px;
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

            .comment-top {
                gap: 8px;
            }

            .comment-avatar {
                width: 36px;
                height: 36px;
            }

            .like-form {
                margin-left: 0;
            }

            .like-button,
            .comment-form button {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .comment-replies {
                margin-left: 8px;
                padding-left: 8px;
            }

            .reply-form {
                margin-left: 8px;
            }

            .reply-form-actions,
            .reply-send,
            .reply-cancel {
                width: 100%;
            }
        }

        .char-counter {
            font-size: 14px;
            color: var(--text-muted);
            text-align: right;
            width: 100%;
            margin-top: -10px;
        }

        .char-counter.is-over {
            color: var(--danger);
        }

        .share-toast {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--surface-3);
            color: var(--text-main);
            padding: 14px 24px;
            box-shadow: var(--shadow-raise);
            font-size: 15px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1600;
        }

        .share-toast.is-visible {
            opacity: 1;
            transform: translateX(-50%) translateY(-8px);
        }

        .similar-posts {
            margin-top: 40px;
            padding: 28px;
            background: var(--surface);
        }

        .similar-posts h4 {
            font-size: 28px;
            margin: 0 0 18px;
        }

        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .similar-card {
            display: grid;
            gap: 10px;
            padding: 14px;
            text-decoration: none;
            background: var(--surface-3);
            color: inherit;
            transition: background-color 0.2s ease;
        }

        .similar-card:hover,
        .similar-card:focus-visible {
            background: var(--surface-hover);
        }

        .similar-card:hover .similar-title {
            text-shadow: var(--glow-hover);
        }

        .similar-img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
            filter: saturate(0.8);
        }

        .similar-title {
            font-size: 16px;
            line-height: 1.4;
            color: var(--text-main);
            text-shadow: var(--glow);
            transition: text-shadow 0.2s ease;
        }

        /* ===== Сворачиваемое описание поста ===== */
        .post-text-wrap {
            max-width: 760px;
            margin: 0 0 28px;
        }

        .post-text-wrap .post-text {
            margin: 0;
            max-width: none;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .post-text-clip {
            position: relative;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .post-text-clip::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 80px;
            background: linear-gradient(to bottom, transparent, var(--surface-2));
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .post-text-wrap.is-collapsed .post-text-clip::after {
            opacity: 1;
        }

        .post-text-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: var(--btn-h);
            margin-top: 6px;
            padding: 0 14px 0 0;
            background: none;
            border: 0;
            color: var(--accent);
            font: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-shadow: var(--glow);
            transition: text-shadow 0.2s ease, color 0.2s ease;
        }

        .post-text-toggle[hidden] {
            display: none;
        }

        .post-text-toggle:hover,
        .post-text-toggle:focus-visible {
            text-shadow: var(--glow-hover);
            text-decoration: underline;
            text-underline-offset: 5px;
        }

        .post-text-toggle svg {
            transition: transform 0.25s ease;
        }

        .post-text-wrap:not(.is-collapsed) .post-text-toggle svg {
            transform: rotate(180deg);
        }
    </style>

    @php
        $imageUrl = $post->image ? $post->image : asset('images/default.png');

        $postAuthorAvatarUrl = $post->user?->avatar ? $post->user->avatar : asset('images/default-avatar.svg');
    @endphp

    <div class="post-page">
        <div class="post-bg" style="background-image: url('{{ $imageUrl }}');"></div>

        <div class="post-container" role="main">
            <div class="img-box">
                <img src="{{ $post->image ?: asset('images/default.png') }}" alt="{{ $post->title }}">
            </div>

            <div class="post-info-box">
                <div class="post-info-header">
                    <div class="post-heading">
                        @php
                            $postCategory =
                                $post->category_id ?? null
                                    ? \Illuminate\Support\Facades\DB::table('categories')
                                        ->where('id', $post->category_id)
                                        ->first(['name', 'slug'])
                                    : null;

                            $postTags = \Illuminate\Support\Facades\DB::table('post_tag')
                                ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                                ->where('post_tag.post_id', $post->id)
                                ->orderBy('post_tag.id')
                                ->get(['tags.name', 'tags.slug']);
                        @endphp

                        @if ($postCategory)
                            <a href="{{ route('posts.index', ['category' => $postCategory->slug]) }}"
                                class="post-category">{{ $postCategory->name }}</a>
                        @endif

                        <h1>{{ $post->title }}</h1>
                        <time class="post-date" datetime="{{ $post->created_at->toIso8601String() }}"
                            title="{{ $post->created_at->format('d.m.Y H:i:s') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" />
                                <polyline points="12 7 12 12 15 14" />
                            </svg>
                            <span>{{ $post->created_at->format('d.m.Y, H:i') }}</span>
                            <span class="post-date-relative">· {{ $post->created_at->diffForHumans() }}</span>
                        </time>

                        @if (($post->visibility ?? 'public') === 'friends')
                            <span class="post-visibility-badge" title="Visible only to friends">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="9" cy="8" r="3.5" />
                                    <path d="M2.5 20a6.5 6.5 0 0 1 13 0" />
                                    <path d="M16 4.6a3.5 3.5 0 0 1 0 6.8" />
                                    <path d="M18 14.3a6.5 6.5 0 0 1 3.5 5.7" />
                                </svg>
                                Friends
                            </span>
                        @elseif (($post->visibility ?? 'public') === 'private')
                            <span class="post-visibility-badge" title="Visible only to the author">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="4.5" y="10.5" width="15" height="10" rx="2" />
                                    <path d="M8 10.5V7.5a4 4 0 0 1 8 0v3" />
                                </svg>
                                Only me
                            </span>
                        @endif

                        @if ($post->is_published !== null && !$post->is_published)
                            <span class="post-visibility-badge post-draft-badge">Draft</span>
                        @endif

                        @if ($postTags->isNotEmpty())
                            <div class="post-tags">
                                @foreach ($postTags as $tag)
                                    <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                                        class="post-tag">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('posts.like', $post->id) }}" method="POST" class="like-form" data-like-form>
                        @csrf
                        <button type="submit" class="like-button {{ $isLiked ? 'is-liked' : '' }}"
                            aria-label="{{ $isLiked ? 'Unlike post' : 'Like post' }}"
                            aria-pressed="{{ $isLiked ? 'true' : 'false' }}" data-like-button>
                            <svg class="like-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>

                        </button>
                    </form>
                    <button class="share-button" id="share-button" aria-label="Share post">
                        <svg class="share-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <polyline points="16 6 12 2 8 6" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="12" y1="2" x2="12" y2="15" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="share-toast" id="share-toast">Link copied!</div>
                </div>

                <div class="post-text-wrap" data-post-text>
                    <div class="post-text-clip" data-post-text-clip>
                        <p class="post-text">{{ $post->content }}</p>
                    </div>
                    <button type="button" class="post-text-toggle" data-post-text-toggle aria-expanded="false" hidden>
                        <span data-toggle-label>Show more</span>
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                @if ($post->user)
                    <a href="{{ route('users.show', $post->user) }}" class="post-author-card"
                        aria-label="Open author profile">
                        <div class="post-author-avatar" style="background-image: url('{{ $postAuthorAvatarUrl }}');">
                        </div>

                        <div class="post-author-copy">
                            <span class="post-author-kicker">Author</span>
                            <span class="post-author-name">{{ $post->user->name }}</span>
                            <span class="post-author-hint">Open profile</span>
                        </div>
                    </a>
                @endif
                <div class="post-stats">
                    <p class="post-views"><strong>Views:</strong>
                        <span data-view-count>{{ $views }}</span>
                    </p>
                    <p class="post-likes-count"><strong>Likes:</strong> <span data-like-count>{{ $likes }}</span>
                    </p>
                </div>
                <h4>Comments:</h4>

                @php
                    $allComments = $post->comments;
                    $knownCommentIds = $allComments->pluck('id');

                    // Корневые комментарии: без parent_id (или если родитель уже удалён)
                    $rootComments = $allComments->filter(
                        fn($c) => is_null($c->parent_id) || !$knownCommentIds->contains($c->parent_id),
                    );

                    // Ответы, сгруппированные по parent_id
                    $repliesMap = $allComments->whereNotNull('parent_id')->groupBy('parent_id');
                @endphp

                @if ($allComments->isEmpty())
                    <p class="text-muted" style="user-select: none;" data-comments-empty>No comments yet.</p>
                @else
                    <div class="comments-list">
                        @foreach ($rootComments as $comment)
                            @include('partials.comment-item', [
                                'comment' => $comment,
                                'post' => $post,
                                'repliesMap' => $repliesMap,
                                'depth' => 0,
                                'parentName' => null,
                            ])
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="comment-form"
                    novalidate>
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="text" name="text" id="comment-text" placeholder="Write a comment..."
                        aria-label="Comment text" required>
                    <div class="char-counter" id="comment-counter">0 / 1000</div>

                    <button type="submit" aria-label="Send comment">Send</button>
                </form>

                @if ($similarPosts->isNotEmpty())
                    <div class="similar-posts">
                        <h4>Similar posts</h4>
                        <div class="similar-grid">
                            @foreach ($similarPosts as $similar)
                                <a href="{{ route('posts.show', $similar->id) }}" class="similar-card">
                                    @if ($similar->image)
                                        <img src="{{ $similar->image ?: asset('images/default.png') }}"
                                            alt="{{ $similar->title }}" class="similar-img">
                                    @endif
                                    <span class="similar-title">{{ $similar->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
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

                    if (likeButton.classList.contains('is-loading')) return;
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

                        if (!response.ok) throw new Error('Like request failed.');

                        const data = await response.json();
                        likeButton.classList.toggle('is-liked', Boolean(data.liked));
                        likeButton.classList.add('is-animating');
                        likeButton.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
                        likeButton.setAttribute('aria-label', data.liked ? 'Unlike post' : 'Like post');
                        likeCount.textContent = data.likes;

                        window.setTimeout(() => likeButton.classList.remove('is-animating'), 520);
                    } catch (error) {
                        window.location.href = '{{ route('login') }}';
                    } finally {
                        likeButton.classList.remove('is-loading');
                    }
                });
            }


            const shareBtn = document.getElementById('share-button');
            const shareToast = document.getElementById('share-toast');

            shareBtn?.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    shareToast?.classList.add('is-visible');
                    setTimeout(() => shareToast?.classList.remove('is-visible'), 2000);
                } catch {
                    const textarea = document.createElement('textarea');
                    textarea.value = window.location.href;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    shareToast?.classList.add('is-visible');
                    setTimeout(() => shareToast?.classList.remove('is-visible'), 2000);
                }
            });

            function attachDeleteHandlers() {
                document.querySelectorAll('[data-comment-delete-form]').forEach((form) => {
                    if (form.dataset.deleteAttached) return;
                    form.dataset.deleteAttached = 'true';

                    const runDelete = async () => {
                        const button = form.querySelector('[data-comment-delete-button]');
                        const commentCard = form.closest('.comment-card');

                        if (!button || !commentCard || button.classList.contains('is-loading'))
                            return;
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

                            if (!response.ok) throw new Error('Delete request failed.');

                            // Удаляем карточку вместе с её ответами
                            const thread = commentCard.closest('.comment-thread') || commentCard;

                            commentCard.classList.add('is-removing');
                            thread.classList.add('is-removing');

                            window.setTimeout(() => {
                                thread.remove();

                                if (commentsList && !commentsList.querySelector(
                                        '.comment-card')) {
                                    commentsList.remove();
                                    commentsList = null;

                                    if (!document.querySelector('[data-comments-empty]')) {
                                        const emptyState = document.createElement('p');
                                        emptyState.className = 'text-muted';
                                        emptyState.dataset.commentsEmpty = 'true';
                                        emptyState.style.userSelect = 'none';
                                        emptyState.textContent = 'No comments yet.';
                                        document.querySelector('.post-info-box h4')
                                            ?.insertAdjacentElement('afterend', emptyState);
                                    }
                                }
                            }, 280);
                        } catch (error) {
                            button.classList.remove('is-loading');
                        }
                    };

                    form.addEventListener('submit', (e) => e.preventDefault());
                    form.addEventListener('confirmed-submit', runDelete);
                });
            }

            attachDeleteHandlers();


            const commentInput = document.getElementById('comment-text');
            const commentCounter = document.getElementById('comment-counter');

            if (commentInput && commentCounter) {
                commentInput.addEventListener('input', () => {
                    const len = commentInput.value.length;
                    commentCounter.textContent = `${len} / 1000`;
                    commentCounter.classList.toggle('is-over', len > 1000);
                });
            }


            // ----- Сборка ветки комментария (общая для комментариев и ответов) -----
            const csrfToken = '{{ csrf_token() }}';
            const commentStoreUrl = '{{ route('comments.store', ['post' => $post->id]) }}';
            const currentPostId = {{ $post->id }};
            const currentUserId = {{ auth()->id() ?? 'null' }};
            const currentIsAdmin = {{ auth()->user()?->isAdmin() ? 'true' : 'false' }};
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            function buildThread(c, depth) {
                const canDelete = currentUserId === c.user_id || currentIsAdmin;

                const deleteFormHtml = canDelete ? `
                    <form action="/comments/${c.id}" method="POST"
                        data-comment-delete-form data-confirm data-confirm-ajax="true"
                        data-confirm-title="Delete comment?"
                        data-confirm-message="This comment will be removed permanently from the post."
                        data-confirm-button="Delete comment">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="comment-delete-button" data-comment-delete-button>Delete</button>
                    </form>` : '';

                const replyButtonHtml = isAuthenticated ?
                    `<button type="button" class="comment-reply-button" data-reply-toggle aria-expanded="false">Reply</button>` :
                    `<a href="{{ route('login') }}" class="comment-reply-button">Reply</a>`;

                const replyFormHtml = isAuthenticated ? `
                    <form action="${commentStoreUrl}" method="POST" class="reply-form" data-reply-form novalidate>
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="post_id" value="${currentPostId}">
                        <input type="hidden" name="parent_id" value="${c.id}">
                        <input type="text" name="text" class="reply-input" placeholder="Write a reply..."
                            maxlength="1000" aria-label="Reply text" required>
                        <div class="reply-form-actions">
                            <button type="submit" class="reply-send">Send</button>
                            <button type="button" class="reply-cancel" data-reply-cancel>Cancel</button>
                        </div>
                    </form>` : '';

                const profileLink = c.profile_url ?
                    `<a href="${c.profile_url}" class="comment-author-link">${c.author}</a>` :
                    c.author;

                const thread = document.createElement('div');
                thread.className = 'comment-thread';
                thread.dataset.depth = depth;
                thread.innerHTML = `
                    <div id="comment-${c.id}" class="comment-card" role="article" aria-label="Comment by ${c.author}">
                        <div class="comment-top">
                            <div class="comment-avatar" style="background-image: url('${c.avatar}');"></div>
                            <div class="comment-body">
                                <p class="comment-author">${profileLink}</p>
                                <p class="comment-text">${c.text}</p>
                                <small class="comment-time">${c.created_at}</small>
                            </div>
                        </div>
                        <div class="comment-actions">
                            ${replyButtonHtml}
                            ${deleteFormHtml}
                        </div>
                    </div>
                    ${replyFormHtml}
                `;

                return thread;
            }

            const commentForm = document.querySelector('.comment-form');

            if (commentForm) {
                commentForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const input = commentForm.querySelector('input[name="text"]');
                    const submitButton = commentForm.querySelector('button[type="submit"]');
                    const text = input?.value.trim();

                    if (!text) return;

                    submitButton.disabled = true;
                    submitButton.textContent = 'Sending...';

                    try {
                        const response = await fetch(commentForm.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(commentForm),
                            credentials: 'same-origin',
                        });

                        if (!response.ok) throw new Error('Comment request failed.');

                        const data = await response.json();

                        if (!data.success) throw new Error('Server error.');

                        const c = data.comment;

                        document.querySelector('[data-comments-empty]')?.remove();

                        if (!commentsList) {
                            commentsList = document.createElement('div');
                            commentsList.className = 'comments-list';
                            commentForm.insertAdjacentElement('beforebegin', commentsList);
                        }

                        const thread = buildThread(c, 0);

                        commentsList.appendChild(thread);
                        attachDeleteHandlers();
                        if (typeof attachReactionHandlers === 'function') attachReactionHandlers(
                            thread);

                        input.value = '';

                        thread.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });

                    } catch (error) {
                        alert('Failed to send comment. Please try again.');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Send';
                    }
                });
            }

            // ----- Ответы на комментарии -----
            document.addEventListener('click', (event) => {
                const toggle = event.target.closest('[data-reply-toggle]');

                if (toggle) {
                    const thread = toggle.closest('.comment-thread');
                    const form = thread?.querySelector(':scope > [data-reply-form]');
                    if (!form) return;

                    const isOpen = form.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                    if (isOpen) form.querySelector('input[name="text"]')?.focus();
                    return;
                }

                const cancel = event.target.closest('[data-reply-cancel]');

                if (cancel) {
                    const form = cancel.closest('[data-reply-form]');
                    form.classList.remove('is-open');
                    form.reset();

                    form.closest('.comment-thread')
                        ?.querySelector(':scope > .comment-card [data-reply-toggle]')
                        ?.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('submit', async (event) => {
                const form = event.target;
                if (!form.matches || !form.matches('form.reply-form')) return;

                event.preventDefault();

                const input = form.querySelector('input[name="text"]');
                const submitButton = form.querySelector('button[type="submit"]');

                if (!input.value.trim() || submitButton.disabled) return;

                submitButton.disabled = true;
                submitButton.textContent = 'Sending...';

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

                    if (!response.ok) throw new Error('Reply request failed.');

                    const data = await response.json();

                    if (!data.success) throw new Error('Server error.');

                    const parentThread = form.closest('.comment-thread');
                    const parentDepth = Number(parentThread.dataset.depth || 0);

                    let replies = parentThread.querySelector(':scope > .comment-replies');

                    if (!replies) {
                        replies = document.createElement('div');
                        replies.className = 'comment-replies' + (parentDepth >= 3 ? ' is-flat' : '');
                        parentThread.appendChild(replies);
                    }

                    const thread = buildThread(data.comment, parentDepth + 1);

                    replies.appendChild(thread);
                    attachDeleteHandlers();
                    if (typeof attachReactionHandlers === 'function') attachReactionHandlers(thread);

                    form.reset();
                    form.classList.remove('is-open');
                    parentThread
                        .querySelector(':scope > .comment-card [data-reply-toggle]')
                        ?.setAttribute('aria-expanded', 'false');

                    thread.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                } catch (error) {
                    alert('Failed to send reply. Please try again.');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Send';
                }
            });
        });
    </script>

    <script>
        // Сворачивание длинного описания поста
        (() => {
            const MAX_LINES = 6; // сколько строк видно в свёрнутом виде

            const wrap = document.querySelector('[data-post-text]');
            if (!wrap) return;

            const clip = wrap.querySelector('[data-post-text-clip]');
            const text = clip.querySelector('.post-text');
            const toggle = wrap.querySelector('[data-post-text-toggle]');
            const label = toggle.querySelector('[data-toggle-label]');

            let expanded = false;
            let timer = null;

            const collapsedHeight = () =>
                Math.round(parseFloat(getComputedStyle(text).lineHeight) * MAX_LINES);

            const measure = () => {
                const limit = collapsedHeight();
                // если текст длиннее лимита меньше чем на одну строку, не прячем
                const needsToggle = text.offsetHeight > limit + limit / MAX_LINES;

                toggle.hidden = !needsToggle;

                if (!needsToggle) {
                    wrap.classList.remove('is-collapsed');
                    clip.style.maxHeight = '';
                    return;
                }

                if (!expanded) {
                    wrap.classList.add('is-collapsed');
                    clip.style.maxHeight = limit + 'px';
                }
            };

            toggle.addEventListener('click', () => {
                expanded = !expanded;
                clearTimeout(timer);

                toggle.setAttribute('aria-expanded', String(expanded));
                label.textContent = expanded ? 'Show less' : 'Show more';
                wrap.classList.toggle('is-collapsed', !expanded);

                clip.style.maxHeight = text.offsetHeight + 'px';

                if (expanded) {
                    // после анимации снимаем ограничение, чтобы текст не обрезался при ресайзе
                    timer = setTimeout(() => {
                        if (expanded) clip.style.maxHeight = 'none';
                    }, 450);
                    return;
                }

                void clip.offsetHeight; // reflow: фиксируем стартовую высоту для анимации
                clip.style.maxHeight = collapsedHeight() + 'px';

                // если начало текста ушло выше экрана, возвращаем к нему
                if (wrap.getBoundingClientRect().top < 100) {
                    if (window.lenis) {
                        window.lenis.scrollTo(wrap, {
                            offset: -120
                        });
                    } else {
                        wrap.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });

            let resizeTimer = null;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(measure, 150);
            });

            measure();
            document.fonts?.ready.then(measure);
        })();
    </script>
@endsection
