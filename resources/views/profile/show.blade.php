@extends('layouts.app')

@section('title', $user->name)

@section('content')
    @php
        $avatarUrl = $user->avatar ? $user->avatar : asset('public/images/default-avatar.svg');
    @endphp

    <style>
        .public-profile-page {
            position: relative;
            min-height: 100vh;
            padding: 132px 24px 72px;
            overflow: hidden;
        }

        .public-profile-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(30px);
            transform: scale(1.1);
            opacity: 0.46;
        }

        .public-profile-backdrop::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(16, 16, 16, 0.36), rgba(16, 16, 16, 0.78)),
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 24%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%);
        }

        .public-profile-shell {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .public-profile-hero {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
            padding: 36px 38px;
            background: rgba(28, 28, 28, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(12px);
        }

        .public-profile-main {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 28px;
            align-items: start;
        }

        .public-profile-avatar {
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            justify-self: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.24);
        }

        .public-profile-copy {
            min-width: 0;
        }

        .public-profile-kicker {
            display: inline-block;
            margin-bottom: 18px;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .public-profile-title {
            margin: 0 0 16px;
            font-size: 52px;
            line-height: 1.03;
        }

        .public-profile-copy-text {
            max-width: 640px;
            margin: 0;
            font-size: 17px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.78);
        }

        .public-profile-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .public-profile-action,
        .public-profile-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .public-profile-action {
            background: #f2ede2;
            color: #1E1E1E;
        }

        .public-profile-action-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #f4f4f4;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .public-profile-action:hover,
        .public-profile-action-secondary:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .public-profile-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .public-profile-stat {
            min-height: 132px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .public-profile-stat-label {
            margin-bottom: 16px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.56);
        }

        .public-profile-stat-value {
            margin: 0 0 10px;
            font-size: 38px;
            line-height: 1;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .public-profile-stat-note {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.72);
        }

        .public-profile-posts {
            padding: 30px;
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .public-profile-section-title {
            margin: 0 0 12px;
            font-size: 30px;
        }

        .public-profile-section-copy {
            margin: 0 0 24px;
            font-size: 15px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.72);
        }

        .public-profile-post-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .public-profile-post-card {
            display: block;
            min-height: 220px;
            padding: 18px;
            text-decoration: none;
            color: inherit;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01)),
                rgba(20, 20, 20, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.22s ease, border-color 0.22s ease, background 0.22s ease;
        }

        .public-profile-post-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02)),
                rgba(22, 22, 22, 0.82);
        }

        .public-profile-post-title {
            margin: 0 0 12px;
            font-size: 21px;
            line-height: 1.2;
        }

        .public-profile-post-copy {
            margin: 0 0 16px;
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.76);
        }

        .public-profile-post-date {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.48);
        }

        .public-profile-empty {
            padding: 22px 24px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.78);
        }

        @media (max-width: 1100px) {
            .public-profile-hero {
                grid-template-columns: 1fr;
            }

            .public-profile-main {
                grid-template-columns: 1fr;
            }

            .public-profile-title {
                font-size: 42px;
            }

            .public-profile-post-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .public-profile-page {
                padding: 112px 14px 40px;
            }

            .public-profile-hero,
            .public-profile-posts {
                padding: 22px 18px;
            }

            .public-profile-avatar {
                width: 180px;
                height: 180px;
            }

            .public-profile-title {
                font-size: 34px;
            }

            .public-profile-stats,
            .public-profile-post-grid {
                grid-template-columns: 1fr;
            }

            .public-profile-actions {
                flex-direction: column;
            }

            .public-profile-action,
            .public-profile-action-secondary {
                width: 100%;
            }
        }
    </style>

    <div class="public-profile-page">
        <div class="public-profile-backdrop" style="background-image: url('{{ $avatarUrl }}');"></div>

        <div class="public-profile-shell">
            <section class="public-profile-hero">
                <div class="public-profile-main">
                    <div class="public-profile-avatar" style="background-image: url('{{ $avatarUrl }}');"></div>

                    <div class="public-profile-copy">
                        <span class="public-profile-kicker">Public profile</span>
                        <h1 class="public-profile-title">{{ $user->name }}</h1>
                        <p class="public-profile-copy-text">
                            A personal page inside the project atmosphere. Here you can see the author's public profile,
                            recent posts, and the general mood of their account.
                        </p>

                        <div class="public-profile-actions">
                            <a href="{{ route('posts.index') }}" class="public-profile-action">Browse posts</a>
                            @if ($viewer && $viewer->id === $user->id)
                                <a href="{{ route('profile.edit') }}" class="public-profile-action-secondary">Open your
                                    profile</a>
                            @elseif ($latestPost)
                                <a href="{{ route('posts.show', $latestPost->id) }}"
                                    class="public-profile-action-secondary">Open latest post</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="public-profile-stats">
                    <div class="public-profile-stat">
                        <div class="public-profile-stat-label">Posts created</div>
                        <p class="public-profile-stat-value">{{ $postsCount }}</p>
                        <div class="public-profile-stat-note">Published posts currently attached to this account.</div>
                    </div>

                    <div class="public-profile-stat">
                        <div class="public-profile-stat-label">Comments received</div>
                        <p class="public-profile-stat-value">{{ $commentsReceived }}</p>
                        <div class="public-profile-stat-note">All comments left under this user's posts.</div>
                    </div>

                    <div class="public-profile-stat">
                        <div class="public-profile-stat-label">Latest post</div>
                        <p class="public-profile-stat-value" style="font-size: 22px; line-height: 1.35;">
                            {{ $latestPost ? $latestPost->title : 'No posts yet' }}
                        </p>
                        <div class="public-profile-stat-note">
                            {{ $latestPost ? $latestPost->created_at->format('d M Y') : 'Nothing has been published here yet.' }}
                        </div>
                    </div>

                    <div class="public-profile-stat">
                        <div class="public-profile-stat-label">Account status</div>
                        <p class="public-profile-stat-value" style="font-size: 22px; line-height: 1.35;">
                            {{ $user->is_admin ? 'Admin' : 'User' }}
                        </p>
                        <div class="public-profile-stat-note">A simple public label for this account inside the project.
                        </div>
                    </div>
                </div>
            </section>

            <section class="public-profile-posts">
                <h2 class="public-profile-section-title">Recent posts</h2>
                <p class="public-profile-section-copy">
                    A quick look at the latest publications from this account.
                </p>

                @if ($recentPosts->isEmpty())
                    <div class="public-profile-empty">This user has not published any posts yet.</div>
                @else
                    <div class="public-profile-post-grid">
                        @foreach ($recentPosts as $post)
                            <a href="{{ route('posts.show', $post->id) }}" class="public-profile-post-card">
                                <h3 class="public-profile-post-title">{{ $post->title }}</h3>
                                <p class="public-profile-post-copy">
                                    {{ \Illuminate\Support\Str::limit($post->content, 110) }}</p>
                                <div class="public-profile-post-date">{{ $post->created_at->format('d M Y') }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
