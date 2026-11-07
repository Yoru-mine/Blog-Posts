@extends('layouts.app')

@section('title', 'Drafts')

@section('content')
    <style>
        .drafts-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
        }

        .drafts-shell {
            max-width: 1080px;
            margin: 0 auto;
            display: grid;
            gap: 24px;
        }

        .drafts-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            padding: 34px 38px;
            background: var(--surface-2);
            box-shadow: var(--shadow-soft);
        }

        .drafts-title {
            margin: 0 0 10px;
            font-size: 44px;
            line-height: 1.05;
            text-shadow: var(--glow);
        }

        .drafts-subtitle {
            margin: 0;
            font-size: 16px;
            color: var(--text-muted);
        }

        .drafts-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* единая кнопка: размер, поведение hover; вариант задаёт только цвет */
        .drafts-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: var(--btn-h);
            padding: var(--btn-pad);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
            background: var(--surface-soft);
            border: 0;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .drafts-button:hover,
        .drafts-button:focus-visible {
            background: var(--surface-hover);
        }

        /* главное действие экрана: «Create post+» в шапке */
        .drafts-button-primary {
            color: var(--accent-contrast);
            background: var(--accent);
            font-weight: 700;
        }

        .drafts-button-primary:hover,
        .drafts-button-primary:focus-visible {
            background: var(--accent-hover);
        }

        /* опасное действие: цвет, отдельный отступ в списке, диалог подтверждения */
        .drafts-button-danger {
            color: var(--danger);
            background: rgba(var(--danger-rgb), 0.14);
        }

        .drafts-button-danger:hover,
        .drafts-button-danger:focus-visible {
            background: rgba(var(--danger-rgb), 0.26);
        }

        .drafts-list {
            display: grid;
            gap: 14px;
        }

        .draft-item {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr) auto;
            align-items: center;
            gap: 22px;
            padding: 16px;
            background: var(--surface-2);
            transition: background-color 0.2s ease;
        }

        .draft-item:hover {
            background: var(--surface-3);
        }

        .draft-thumb {
            display: block;
            width: 180px;
            height: 118px;
            overflow: hidden;
            background: var(--surface);
        }

        .draft-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: brightness(0.8) saturate(0.7);
        }

        .draft-thumb-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: var(--text-muted);
        }

        .draft-body {
            min-width: 0;
        }

        .draft-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .draft-label {
            padding: 2px 10px;
            font-size: 14px;
            font-weight: 700;
            color: var(--accent-contrast);
            background: var(--accent);
        }

        .draft-title {
            margin: 0 0 8px;
            font-size: 22px;
            line-height: 1.25;
            text-shadow: var(--glow);
        }

        .draft-title a {
            color: inherit;
            text-decoration: none;
        }

        .draft-title a:hover {
            text-shadow: var(--glow-hover);
        }

        .draft-excerpt {
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .draft-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 170px;
        }

        .draft-actions form {
            margin: 0;
        }

        .draft-actions form:last-child {
            margin-top: 8px;
        }

        .draft-actions .drafts-button {
            width: 100%;
        }

        .drafts-empty {
            padding: 56px 24px;
            text-align: center;
            background: var(--surface-2);
        }

        .drafts-empty h2 {
            margin: 0 0 10px;
            font-size: 26px;
            text-shadow: var(--glow);
        }

        .drafts-empty p {
            max-width: 460px;
            margin: 0 auto;
            font-size: 16px;
            line-height: 1.7;
            color: var(--text-muted);
        }

        .drafts-pagination {
            margin-top: 8px;
        }

        @media (max-width: 860px) {
            .draft-item {
                grid-template-columns: 140px minmax(0, 1fr);
            }

            .draft-thumb {
                width: 140px;
                height: 100px;
            }

            .draft-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .draft-actions form:last-child {
                margin-top: 0;
                margin-left: auto;
            }

            .draft-actions .drafts-button {
                width: auto;
            }
        }

        @media (max-width: 720px) {
            .drafts-page {
                padding: 112px 14px 36px;
            }

            .drafts-header {
                padding: 22px 18px;
            }

            .drafts-title {
                font-size: 34px;
            }

            .draft-item {
                grid-template-columns: 1fr;
            }

            .draft-thumb {
                width: 100%;
                height: 190px;
            }

            .draft-actions .drafts-button {
                flex: 1 1 auto;
            }
        }
    </style>

    @php
        $draftItems = method_exists($posts, 'items') ? $posts->items() : $posts;
        $draftsTotal = method_exists($posts, 'total') ? $posts->total() : count($draftItems);
        $categoryNames = \Illuminate\Support\Facades\DB::table('categories')->pluck('name', 'id');
    @endphp

    <div class="drafts-page">
        <div class="drafts-shell">
            <header class="drafts-header">
                <div>
                    <h1 class="drafts-title">Your drafts</h1>
                    <p class="drafts-subtitle">
                        {{ $draftsTotal }} {{ $draftsTotal === 1 ? 'draft' : 'drafts' }} saved. Only you can see them.
                    </p>
                </div>

                <div class="drafts-header-actions">
                    <a href="{{ route('posts.create') }}" class="drafts-button drafts-button-primary">Create post+</a>
                    <a href="{{ route('posts.index') }}" class="drafts-button">All posts</a>
                </div>
            </header>

            @if ($draftsTotal === 0)
                <div class="drafts-empty">
                    <h2>No drafts yet</h2>
                    <p>Start writing and press "Save as draft" to keep your post here until it is ready.</p>
                </div>
            @else
                <div class="drafts-list">
                    @foreach ($posts as $post)
                        @php
                            $draftCategory = $categoryNames[$post->category_id ?? 0] ?? null;
                            $draftTitle = trim((string) $post->title) !== '' ? $post->title : 'Untitled draft';
                            $draftExcerpt =
                                trim((string) $post->content) !== ''
                                    ? \Illuminate\Support\Str::limit(strip_tags($post->content), 150)
                                    : 'No text yet.';
                        @endphp

                        <article class="draft-item">
                            <a href="{{ route('posts.edit', $post->id) }}" class="draft-thumb"
                                aria-label="Edit {{ $draftTitle }}">
                                @if ($post->image)
                                    <img src="{{ $post->image }}" alt="{{ $draftTitle }}">
                                @else
                                    <div class="draft-thumb-empty">No image</div>
                                @endif
                            </a>

                            <div class="draft-body">
                                <div class="draft-meta">
                                    <span class="draft-label">Draft</span>
                                    @if ($draftCategory)
                                        <span>{{ $draftCategory }}</span>
                                    @endif
                                    <span>Edited {{ $post->updated_at->diffForHumans() }}</span>
                                </div>

                                <h2 class="draft-title">
                                    <a href="{{ route('posts.edit', $post->id) }}">{{ $draftTitle }}</a>
                                </h2>

                                <p class="draft-excerpt">{{ $draftExcerpt }}</p>
                            </div>

                            <div class="draft-actions">
                                <a href="{{ route('posts.edit', $post->id) }}" class="drafts-button">Continue editing</a>

                                @if (\Illuminate\Support\Facades\Route::has('posts.publish'))
                                    <form method="POST" action="{{ route('posts.publish', $post->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="drafts-button">Publish</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" data-confirm
                                    data-confirm-title="Delete draft?"
                                    data-confirm-message="This draft will be deleted permanently."
                                    data-confirm-button="Delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="drafts-button drafts-button-danger">Delete</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if (method_exists($posts, 'links'))
                    <div class="drafts-pagination">
                        {{ $posts->onEachSide(1)->links('partials.pagination') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
