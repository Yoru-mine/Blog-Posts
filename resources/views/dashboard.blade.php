@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $hasDraftStatus = \Illuminate\Support\Facades\Schema::hasColumn('posts', 'is_published');
        $draftsCount = $hasDraftStatus
            ? \Illuminate\Support\Facades\DB::table('posts')
                ->where('user_id', auth()->id())
                ->where('is_published', false)
                ->count()
            : 0;
        $recentDrafts =
            $draftsCount > 0
                ? \Illuminate\Support\Facades\DB::table('posts')
                    ->where('user_id', auth()->id())
                    ->where('is_published', false)
                    ->orderByDesc('updated_at')
                    ->limit(3)
                    ->get(['id', 'title', 'content', 'image', 'updated_at'])
                : collect();
    @endphp
    <style>
        .dashboard-page {
            position: relative;
            min-height: 100vh;
            padding: 130px 24px 72px;
        }

        .dashboard-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(30px) brightness(0.4) saturate(0.7);
            transform: scale(1.1);
            opacity: 0.45;
            z-index: 0;
        }

        .dashboard-shell {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .dashboard-hero {
            background: var(--surface-2);
            box-shadow: var(--shadow-soft);
            padding: 42px 44px;
            display: grid;
            grid-template-columns: 1.4fr 0.9fr;
            gap: 28px;
        }

        .dashboard-title {
            font-size: 56px;
            line-height: 1.02;
            margin: 0 0 16px;
            text-wrap: balance;
            text-shadow: var(--glow);
        }

        .dashboard-copy {
            max-width: 700px;
            font-size: 18px;
            line-height: 1.8;
            color: var(--text-muted);
            margin: 0;
        }

        .dashboard-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        /* единая кнопка: размер и hover одинаковы, различается только цвет */
        .dashboard-action,
        .dashboard-action-secondary,
        .recent-post-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 170px;
            min-height: var(--btn-h);
            padding: var(--btn-pad);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            color: var(--text-main);
            background: var(--surface-soft);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dashboard-action-secondary:hover,
        .dashboard-action-secondary:focus-visible,
        .recent-post-actions a:hover,
        .recent-post-actions a:focus-visible {
            background: var(--surface-hover);
        }

        /* главное действие экрана: создать пост */
        .dashboard-action {
            color: var(--accent-contrast);
            background: var(--accent);
            font-weight: 700;
        }

        .dashboard-action:hover,
        .dashboard-action:focus-visible {
            background: var(--accent-hover);
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            align-content: start;
        }

        .dashboard-stat {
            background: var(--surface-3);
            padding: 22px 20px;
            min-height: 138px;
        }

        .dashboard-stat-label {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        .dashboard-stat-value {
            font-size: 40px;
            line-height: 1;
            margin: 0 0 10px;
            text-shadow: var(--glow);
        }

        .dashboard-stat-note {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 28px;
        }

        .dashboard-panel {
            background: var(--surface-2);
            box-shadow: var(--shadow-soft);
            padding: 30px;
        }

        .panel-title {
            font-size: 28px;
            margin: 0 0 10px;
            text-shadow: var(--glow);
        }

        .panel-subtitle {
            font-size: 16px;
            color: var(--text-muted);
            margin: 0 0 26px;
            line-height: 1.7;
        }

        .recent-posts {
            display: grid;
            gap: 14px;
        }

        .recent-post {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 18px;
            align-items: center;
            background: var(--surface-3);
            padding: 16px;
            transition: background-color 0.2s ease;
        }

        .recent-post:hover {
            background: var(--surface-hover);
        }

        .recent-post-image {
            width: 120px;
            height: 96px;
            object-fit: cover;
            display: block;
            background: var(--surface-soft);
            filter: saturate(0.8);
        }

        .recent-post-content h3 {
            font-size: 20px;
            margin: 0 0 10px;
            text-shadow: var(--glow);
        }

        .recent-post-content p {
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-muted);
            margin: 0 0 10px;
        }

        .recent-post-meta {
            font-size: 14px;
            color: var(--text-muted);
        }

        .recent-post-actions {
            display: flex;
            align-items: center;
        }

        .recent-post-actions a {
            min-width: 0;
        }

        .empty-state {
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--surface-3);
            padding: 28px;
        }

        .empty-state p {
            font-size: 16px;
            line-height: 1.7;
            color: var(--text-muted);
            margin: 0;
            max-width: 500px;
        }

        .dashboard-links {
            display: grid;
            gap: 14px;
        }

        .dashboard-link-card {
            display: block;
            text-decoration: none;
            color: inherit;
            background: var(--surface-3);
            padding: 20px;
            transition: background-color 0.2s ease;
        }

        .dashboard-link-card:hover,
        .dashboard-link-card:focus-visible {
            background: var(--surface-hover);
        }

        .dashboard-link-card h3 {
            font-size: 18px;
            margin: 0 0 10px;
            text-shadow: var(--glow);
            transition: text-shadow 0.2s ease;
        }

        .dashboard-link-card:hover h3 {
            text-shadow: var(--glow-hover);
        }

        .dashboard-link-card p {
            margin: 0;
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-muted);
        }

        .dashboard-draft-badge {
            display: inline-block;
            margin-left: 10px;
            padding: 2px 10px;
            font-size: 14px;
            font-weight: 700;
            vertical-align: middle;
            color: var(--accent-contrast);
            background: var(--accent);
            text-shadow: none;
        }

        .dashboard-action-count {
            padding: 1px 9px;
            font-size: 14px;
            font-weight: 700;
            color: var(--accent-contrast);
            background: var(--accent);
        }

        /* блок черновиков отделён фоном и отступом, без линии */
        .dashboard-drafts {
            margin-top: 34px;
            padding: 24px;
            background: var(--surface);
        }

        .dashboard-drafts-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .dashboard-drafts-head h2 {
            margin: 0;
            font-size: 22px;
            text-shadow: var(--glow);
        }

        .dashboard-drafts-head a {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            padding: 0 14px;
            font-size: 15px;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            text-shadow: var(--glow);
            transition: background-color 0.2s ease, text-shadow 0.2s ease;
        }

        .dashboard-drafts-head a:hover,
        .dashboard-drafts-head a:focus-visible {
            background: var(--surface-soft);
            text-shadow: var(--glow-hover);
        }

        @media (max-width: 1100px) {

            .dashboard-hero,
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-title {
                font-size: 44px;
            }
        }

        @media (max-width: 720px) {
            .dashboard-page {
                padding: 112px 14px 36px;
            }

            .dashboard-hero,
            .dashboard-panel {
                padding: 22px 18px;
            }

            .dashboard-title {
                font-size: 34px;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .recent-post {
                grid-template-columns: 1fr;
            }

            .recent-post-image {
                width: 100%;
                height: 180px;
            }

            .dashboard-actions {
                flex-direction: column;
            }

            .dashboard-action,
            .dashboard-action-secondary,
            .recent-post-actions a {
                width: 100%;
            }
        }
    </style>

    <div class="dashboard-page">
        <div class="dashboard-backdrop"
            style="background-image: url('{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}')">
        </div>
        <div class="dashboard-shell">
            <section class="dashboard-hero">
                <div>
                    <h1 class="dashboard-title">Welcome back, {{ $user?->name ?? 'writer' }}</h1>
                    <p class="dashboard-copy">
                        This is your private workspace. From here you can create a new post, adjust your profile, and
                        quickly return to the pieces you've published most recently.
                    </p>

                    <div class="dashboard-actions">
                        <a href="{{ route('posts.create') }}" class="dashboard-action">Create a new post</a>
                        <a href="{{ route('profile.edit') }}" class="dashboard-action-secondary">Edit profile</a>
                        @if (\Illuminate\Support\Facades\Route::has('posts.drafts'))
                            <a href="{{ route('posts.drafts') }}" class="dashboard-action-secondary">
                                Drafts
                                @if ($draftsCount > 0)
                                    <span class="dashboard-action-count">{{ $draftsCount }}</span>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>

                <div class="dashboard-stats">
                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Posts created</div>
                        <p class="dashboard-stat-value">{{ $stats['posts_count'] }}</p>
                        <div class="dashboard-stat-note">A quick look at how many stories you have already published.</div>
                    </div>

                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Comments received</div>
                        <p class="dashboard-stat-value">{{ $stats['comments_count'] }}</p>
                        <div class="dashboard-stat-note">Audience activity across the posts currently linked to your
                            account.</div>
                    </div>

                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Latest post</div>
                        <p class="dashboard-stat-value">
                            {{ $stats['latest_post'] ? $stats['latest_post']->created_at->format('d M') : '—' }}</p>
                        <div class="dashboard-stat-note">
                            {{ $stats['latest_post'] ? $stats['latest_post']->title : 'No posts yet. Your next one can start here.' }}
                        </div>
                    </div>

                    <div class="dashboard-stat">
                        <div class="dashboard-stat-label">Drafts</div>
                        <p class="dashboard-stat-value">{{ $draftsCount }}</p>
                        <div class="dashboard-stat-note">Unfinished posts saved only for you.</div>
                    </div>
                </div>
            </section>

            <section class="dashboard-grid">
                <div class="dashboard-panel">
                    <h2 class="panel-title">Your recent posts</h2>
                    <p class="panel-subtitle">The latest posts tied to your account. Open them to review how they look live,
                        or jump directly into editing.</p>

                    @if ($recentPosts->isEmpty())
                        <div class="empty-state">
                            <p>You don't have any posts linked to your account yet. Create one and it will appear here
                                automatically.</p>
                        </div>
                    @else
                        <div class="recent-posts">
                            @foreach ($recentPosts as $post)
                                <article class="recent-post">
                                    <img src="{{ $post->image ?: asset('images/default.png') }}" alt="{{ $post->title }}"
                                        class="recent-post-image">

                                    <div class="recent-post-content">
                                        <h3>
                                            {{ $post->title }}
                                            @if ($post->is_published !== null && !$post->is_published)
                                                <span class="dashboard-draft-badge">Draft</span>
                                            @endif
                                        </h3>
                                        <p>{{ \Illuminate\Support\Str::limit($post->content, 120) }}</p>
                                        <div class="recent-post-meta">Updated {{ $post->updated_at->diffForHumans() }}
                                        </div>
                                    </div>

                                    <div class="recent-post-actions">
                                        <a href="{{ route('posts.edit', $post->id) }}">Edit post</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    @if ($draftsCount > 0)
                        <div class="dashboard-drafts">
                            <div class="dashboard-drafts-head">
                                <h2>Your drafts</h2>
                                @if (\Illuminate\Support\Facades\Route::has('posts.drafts'))
                                    <a href="{{ route('posts.drafts') }}">View all {{ $draftsCount }}</a>
                                @endif
                            </div>

                            <div class="recent-posts">
                                @foreach ($recentDrafts as $draft)
                                    <article class="recent-post">
                                        <img src="{{ $draft->image ?: asset('images/default.png') }}" alt=""
                                            class="recent-post-image">

                                        <div class="recent-post-content">
                                            <h3>
                                                {{ trim((string) $draft->title) !== '' ? $draft->title : 'Untitled draft' }}
                                                <span class="dashboard-draft-badge">Draft</span>
                                            </h3>
                                            <p>{{ trim((string) $draft->content) !== '' ? \Illuminate\Support\Str::limit(strip_tags($draft->content), 120) : 'No text yet.' }}
                                            </p>
                                            <div class="recent-post-meta">Edited
                                                {{ \Illuminate\Support\Carbon::parse($draft->updated_at)->diffForHumans() }}
                                            </div>
                                        </div>

                                        <div class="recent-post-actions">
                                            <a href="{{ route('posts.edit', $draft->id) }}">Continue editing</a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="dashboard-panel">
                    <h2 class="panel-title">Quick access</h2>
                    <p class="panel-subtitle">Shortcuts to the parts of the project you'll probably use most while writing
                        and managing your content.</p>

                    <div class="dashboard-links">
                        <a href="{{ route('home') }}" class="dashboard-link-card">
                            <h3>Open the main page</h3>
                            <p>See how the landing looks right now with the latest public content.</p>
                        </a>

                        <a href="{{ route('posts.index') }}" class="dashboard-link-card">
                            <h3>Browse all posts</h3>
                            <p>Review the full feed and open any post to check comments, likes, and views.</p>
                        </a>

                    </div>
                </aside>
            </section>
        </div>
    </div>
@endsection
