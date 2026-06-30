@extends('layouts.app')

@section('title', 'Admin panel')

@section('content')
    <style>
        .admin-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 32%);
        }

        .admin-shell {
            max-width: 1260px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .admin-hero,
        .admin-card,
        .admin-section {
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .admin-hero {
            padding: 38px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 24px;
        }

        .admin-kicker {
            display: inline-block;
            margin-bottom: 18px;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .admin-title {
            margin: 0 0 14px;
            font-size: 48px;
            line-height: 1.04;
        }

        .admin-copy {
            max-width: 720px;
            margin: 0;
            font-size: 17px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.78);
        }

        .admin-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .admin-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            background: #f2ede2;
            color: #1E1E1E;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .admin-action:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            align-content: start;
        }

        .admin-card {
            padding: 22px 20px;
            min-height: 138px;
        }

        .admin-stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.58);
            margin-bottom: 18px;
        }

        .admin-stat-value {
            font-size: 40px;
            line-height: 1;
            margin: 0 0 10px;
        }

        .admin-stat-note {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
        }

        .admin-shortcuts {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .admin-shortcut {
            display: block;
            text-decoration: none;
            color: inherit;
            padding: 24px;
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .admin-shortcut:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, 0.06);
        }

        .admin-shortcut h2 {
            margin: 0 0 12px;
            font-size: 24px;
        }

        .admin-shortcut p {
            margin: 0;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.72);
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .admin-section {
            padding: 26px;
        }

        .admin-section h3 {
            margin: 0 0 18px;
            font-size: 24px;
        }

        .admin-feed {
            display: grid;
            gap: 12px;
        }

        .admin-feed-item {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-feed-title {
            margin: 0 0 8px;
            font-size: 15px;
            color: #f4f4f4;
        }

        .admin-feed-meta {
            font-size: 13px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.58);
        }

        @media (max-width: 1100px) {
            .admin-hero,
            .admin-grid,
            .admin-shortcuts {
                grid-template-columns: 1fr;
            }

            .admin-title {
                font-size: 38px;
            }
        }

        @media (max-width: 720px) {
            .admin-page {
                padding: 112px 14px 36px;
            }

            .admin-hero,
            .admin-section,
            .admin-shortcut {
                padding: 22px 18px;
            }

            .admin-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="admin-page">
        <div class="admin-shell">
            <section class="admin-hero">
                <div>
                    <span class="admin-kicker">Administrator only</span>
                    <h1 class="admin-title">Admin panel</h1>
                    <p class="admin-copy">
                        This is your control center. From here you can quickly move between posts, comments, users, and the main moderation areas of the project.
                    </p>

                    <div class="admin-actions">
                        <a href="{{ route('admin.posts') }}" class="admin-action">Manage posts</a>
                        <a href="{{ route('admin.comments') }}" class="admin-action">Manage comments</a>
                        <a href="{{ route('admin.users') }}" class="admin-action">Manage users</a>
                    </div>
                </div>

                <div class="admin-stats">
                    <article class="admin-card">
                        <div class="admin-stat-label">All posts</div>
                        <p class="admin-stat-value">{{ $stats['posts'] }}</p>
                        <div class="admin-stat-note">Total number of posts currently stored in the project.</div>
                    </article>

                    <article class="admin-card">
                        <div class="admin-stat-label">All comments</div>
                        <p class="admin-stat-value">{{ $stats['comments'] }}</p>
                        <div class="admin-stat-note">Useful for moderation and checking overall activity.</div>
                    </article>

                    <article class="admin-card">
                        <div class="admin-stat-label">All users</div>
                        <p class="admin-stat-value">{{ $stats['users'] }}</p>
                        <div class="admin-stat-note">Registered users that can sign in and interact with the site.</div>
                    </article>

                    <article class="admin-card">
                        <div class="admin-stat-label">Admins</div>
                        <p class="admin-stat-value">{{ $stats['admins'] }}</p>
                        <div class="admin-stat-note">Accounts that currently have elevated access.</div>
                    </article>
                </div>
            </section>

            <section class="admin-shortcuts">
                <a href="{{ route('admin.posts') }}" class="admin-shortcut">
                    <h2>Posts</h2>
                    <p>Open the full list of posts, check owners, and move into editing from the admin side.</p>
                </a>

                <a href="{{ route('admin.comments') }}" class="admin-shortcut">
                    <h2>Comments</h2>
                    <p>Review the latest comments, spot noise quickly, and prepare moderation actions.</p>
                </a>

                <a href="{{ route('admin.users') }}" class="admin-shortcut">
                    <h2>Users</h2>
                    <p>See who is registered, who is an admin, and which accounts might need role changes.</p>
                </a>
            </section>

            <section class="admin-grid">
                <div class="admin-section">
                    <h3>Latest posts</h3>
                    <div class="admin-feed">
                        @forelse ($latestPosts as $post)
                            <div class="admin-feed-item">
                                <p class="admin-feed-title">{{ $post->title }}</p>
                                <div class="admin-feed-meta">Created {{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="admin-feed-item">
                                <div class="admin-feed-meta">No posts yet.</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="admin-section">
                    <h3>Latest comments</h3>
                    <div class="admin-feed">
                        @forelse ($latestComments as $comment)
                            <div class="admin-feed-item">
                                <p class="admin-feed-title">{{ $comment->user?->name ?? $comment->author }}</p>
                                <div class="admin-feed-meta">{{ \Illuminate\Support\Str::limit($comment->text, 70) }}</div>
                            </div>
                        @empty
                            <div class="admin-feed-item">
                                <div class="admin-feed-meta">No comments yet.</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="admin-section">
                    <h3>Latest users</h3>
                    <div class="admin-feed">
                        @forelse ($latestUsers as $user)
                            <div class="admin-feed-item">
                                <p class="admin-feed-title">{{ $user->name }}</p>
                                <div class="admin-feed-meta">{{ $user->email }}</div>
                            </div>
                        @empty
                            <div class="admin-feed-item">
                                <div class="admin-feed-meta">No users yet.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
