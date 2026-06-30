@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-page {
            position: relative;
            min-height: 100vh;
            padding: 130px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 24%),
                radial-gradient(circle at top right, rgba(255, 220, 220, 0.08), transparent 20%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.02), transparent 30%);
        }

        .dashboard-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(30px) brightness(0.4);
            transform: scale(1.1);
            opacity: 0.5;
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
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02)),
                rgba(26, 26, 26, 0.56);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
            padding: 42px 44px;
            display: grid;
            grid-template-columns: 1.4fr 0.9fr;
            gap: 28px;
        }

        .dashboard-kicker {
            display: inline-block;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
            margin-bottom: 18px;
        }

        .dashboard-title {
            font-size: 56px;
            line-height: 1.02;
            margin: 0 0 16px;
            text-wrap: balance;
        }

        .dashboard-copy {
            max-width: 700px;
            font-size: 18px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .dashboard-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 34px;
        }

        .dashboard-action,
        .dashboard-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 170px;
            min-height: 54px;
            padding: 0 22px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease, background 0.2s ease;
        }

        .dashboard-action {
            background: #f2ede2;
            color: #1E1E1E;
        }

        .dashboard-action-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #f4f4f4;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .dashboard-action:hover,
        .dashboard-action-secondary:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            align-content: start;
        }

        .dashboard-stat {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 22px 20px;
            min-height: 138px;
        }

        .dashboard-stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.58);
            margin-bottom: 18px;
        }

        .dashboard-stat-value {
            font-size: 40px;
            line-height: 1;
            margin: 0 0 10px;
        }

        .dashboard-stat-note {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.5;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 28px;
        }

        .dashboard-panel {
            background: rgba(30, 30, 30, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
            padding: 30px;
        }

        .panel-title {
            font-size: 28px;
            margin: 0 0 10px;
        }

        .panel-subtitle {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0 0 26px;
            line-height: 1.7;
        }

        .recent-posts {
            display: grid;
            gap: 16px;
        }

        .recent-post {
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 18px;
            align-items: stretch;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px;
        }

        .recent-post-image {
            width: 120px;
            height: 96px;
            object-fit: cover;
            display: block;
            background: rgba(255, 255, 255, 0.06);
        }

        .recent-post-content h3 {
            font-size: 20px;
            margin: 0 0 10px;
        }

        .recent-post-content p {
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.76);
            margin: 0 0 10px;
        }

        .recent-post-meta {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.52);
        }

        .recent-post-actions {
            display: flex;
            align-items: center;
        }

        .recent-post-actions a {
            color: #f4f4f4;
            text-decoration: none;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 11px 14px;
            white-space: nowrap;
        }

        .recent-post-actions a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .empty-state {
            min-height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 28px;
        }

        .empty-state p {
            font-size: 16px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.72);
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
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .dashboard-link-card:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.06);
        }

        .dashboard-link-card h3 {
            font-size: 18px;
            margin: 0 0 10px;
        }

        .dashboard-link-card p {
            margin: 0;
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.72);
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
            .dashboard-action-secondary {
                width: 100%;
            }
        }
    </style>

    <div class="dashboard-page">
        <div class="dashboard-backdrop"
            style="background-image: url('{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.svg') }}');">
        </div>
        <div class="dashboard-shell">
            <section class="dashboard-hero">
                <div>
                    <span class="dashboard-kicker">Personal dashboard</span>
                    <h1 class="dashboard-title">Welcome back, {{ $user?->name ?? 'writer' }}</h1>
                    <p class="dashboard-copy">
                        This is your private workspace. From here you can create a new post, adjust your profile, and
                        quickly return to the pieces you've published most recently.
                    </p>

                    <div class="dashboard-actions">
                        <a href="{{ route('posts.create') }}" class="dashboard-action">Create a new post</a>
                        <a href="{{ route('profile.edit') }}" class="dashboard-action-secondary">Edit profile</a>
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
                        <div class="dashboard-stat-label">Quick focus</div>
                        <p class="dashboard-stat-value">{{ $recentPosts->count() }}</p>
                        <div class="dashboard-stat-note">Recent posts displayed below for fast editing and review.</div>
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
                            <a href="{{ route('posts.create') }}" class="dashboard-action">Create first post</a>
                        </div>
                    @else
                        <div class="recent-posts">
                            @foreach ($recentPosts as $post)
                                <article class="recent-post">
                                    <img src="{{ $post->image ? asset('storage/' . $post->image) : asset('images/default.png') }}"
                                        alt="{{ $post->title }}" class="recent-post-image">

                                    <div class="recent-post-content">
                                        <h3>{{ $post->title }}</h3>
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

                        <a href="{{ route('profile.edit') }}" class="dashboard-link-card">
                            <h3>Profile settings</h3>
                            <p>Update your name, password, and other account information.</p>
                        </a>
                    </div>
                </aside>
            </section>
        </div>
    </div>
@endsection
