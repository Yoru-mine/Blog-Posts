@extends('layouts.app')

@section('title', 'Manage comments')

@section('content')
    <style>
        .admin-list-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 32%);
        }

        .admin-list-shell {
            max-width: 1260px;
            margin: 0 auto;
            display: grid;
            gap: 26px;
        }

        .admin-list-hero,
        .admin-table-card {
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .admin-list-hero {
            padding: 34px;
        }

        .admin-list-title {
            margin: 0 0 12px;
            font-size: 42px;
        }

        .admin-list-copy {
            margin: 0;
            font-size: 16px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.75);
        }

        .admin-table-card {
            overflow: hidden;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 18px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            vertical-align: top;
        }

        .admin-table th {
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.54);
        }

        .admin-table td {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.82);
        }

        .admin-table a {
            color: #f2ede2;
            text-decoration: none;
        }

        .admin-table a:hover {
            text-decoration: underline;
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            background: rgba(255, 214, 214, 0.9);
            color: #3a1b1b;
            font-family: inherit;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .admin-list-page {
                padding: 112px 14px 36px;
            }

            .admin-list-hero {
                padding: 22px 18px;
            }

            .admin-table-card {
                overflow-x: auto;
            }
        }
    </style>

    <div class="admin-list-page">
        <div class="admin-list-shell">
            <section class="admin-list-hero">
                <h1 class="admin-list-title">Manage comments</h1>
                <p class="admin-list-copy">A moderation-friendly table of the latest comments with author names and related
                    posts.</p>
            </section>

            <section class="admin-table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Comment</th>
                            <th>Post</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                            <tr>
                                <td>
                                    @if ($comment->user)
                                        <a href="{{ route('users.show', $comment->user) }}">{{ $comment->user->name }}</a>
                                    @else
                                        {{ $comment->author }}
                                    @endif
                                </td>
                                <td><a
                                        href="{{ route('posts.show', $comment->post_id) }}#comment-{{ $comment->id }}">{{ $comment->text }}</a>
                                </td>
                                <td>{{ $comment->post?->title ?? 'Deleted post' }}</td>
                                <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <form
                                            action="{{ route('comments.destroy', $comment->id) }}"
                                            method="POST"
                                            data-confirm
                                            data-confirm-title="Delete comment?"
                                            data-confirm-message="This comment will be removed permanently."
                                            data-confirm-button="Delete comment"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action-button">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No comments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            @if (method_exists($comments, 'links'))
                {{ $comments->onEachSide(1)->links('partials.pagination') }}
            @endif
        </div>
    </div>
@endsection
