@extends('layouts.app')

@section('title', 'Posts')

@section('content')

    <style>
        .posts-page {
            position: relative;
            min-height: calc(100vh - 88px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 64px 0 72px;
        }

        .posts-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(30px) brightness(0.3) saturate(0.7);
            transform: scale(1.1);
            opacity: 0.45;
            z-index: 0;
            pointer-events: none;
        }

        .posts-container,
        .posts-pagination,
        .posts-toolbar {
            position: relative;
            z-index: 1;
        }

        .posts-toolbar {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 0 20px;
        }

        /* единая кнопка страницы: главное действие — «Create post+» */
        .posts-create-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: var(--btn-h);
            padding: var(--btn-pad);
            background: var(--accent);
            color: var(--accent-contrast);
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .posts-create-button:hover,
        .posts-create-button:focus-visible {
            background: var(--accent-hover);
        }

        /* вторичное действие */
        .posts-drafts-button {
            color: var(--text-main);
            background: var(--surface-soft);
            font-weight: 600;
        }

        .posts-drafts-button:hover,
        .posts-drafts-button:focus-visible {
            background: var(--surface-hover);
        }

        .posts-drafts-count {
            min-width: 24px;
            padding: 1px 8px;
            font-size: 14px;
            text-align: center;
            color: var(--accent-contrast);
            background: var(--accent);
        }

        .posts-filters {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            max-width: 1200px;
            margin: 22px auto 0;
            padding: 0 20px;
        }

        .posts-filter {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            padding: 0 18px;
            font-size: 15px;
            color: var(--text-main);
            background: var(--surface-soft);
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease, text-shadow 0.2s ease;
        }

        .posts-filter:hover,
        .posts-filter:focus-visible {
            background: var(--surface-hover);
            text-shadow: var(--glow);
        }

        /* выбранный фильтр — состояние, а не действие: подсвечен текстом и фоном */
        .posts-filter.is-active {
            background: var(--surface-hover);
            color: var(--accent);
            font-weight: 700;
            text-shadow: var(--glow);
        }

        .posts-active-tag {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 16px;
            font-size: 15px;
            color: var(--text-muted);
        }

        .posts-active-tag strong {
            padding: 6px 14px;
            color: var(--accent);
            background: var(--surface-hover);
            text-shadow: var(--glow);
        }

        .posts-active-tag a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: var(--btn-h);
            min-height: var(--btn-h);
            color: var(--text-muted);
            font-size: 22px;
            line-height: 1;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .posts-active-tag a:hover,
        .posts-active-tag a:focus-visible {
            color: var(--text-main);
            background: var(--surface-hover);
        }

        .card-category {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 2;
            padding: 4px 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
        }

        .card-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 10px;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.4s ease;
        }

        .card:hover .card-tags {
            max-height: 48px;
            opacity: 1;
            margin-top: 10px;
        }

        .posts-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 45px;
            padding: 40px;
            max-width: 2135px;
            margin: 0 auto;
        }

        .card {
            display: flex;
            position: relative;
            flex-direction: column;
            width: 500px;
            height: 380px;
            overflow: hidden;
            background: var(--surface-2);
            box-shadow: var(--shadow-raise);
            transition: box-shadow 0.3s;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.5) saturate(0.65);
        }

        .title2 {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            color: var(--text-main);
            z-index: 2;
        }

        .title2 h3 {
            margin: 0;
            font-size: 22px;
            line-height: 1.25;
            text-shadow: var(--glow);
            transition: all 0.4s ease;
        }

        .title2 p {
            max-height: 0;
            opacity: 0;
            transform: translateY(10px);
            overflow: hidden;
            font-size: 15px;
            line-height: 1.5;
            transition: all 0.4s ease;
        }

        .card:hover .title2 h3 {
            transform: translateY(-10px);
            text-shadow: var(--glow-hover);
        }

        .card:hover .title2 p {
            max-height: 100px;
            opacity: 1;
            transform: translateY(0);
            margin-top: 10px;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: 0.3s;
        }

        .card:hover::after {
            opacity: 1;
        }

        .post-card {
            text-decoration: none;
            color: inherit;
        }

        .posts-pagination {
            margin-top: 8px;
        }

        @media (max-width: 900px) {
            .posts-page {
                min-height: auto;
                padding: 112px 0 56px;
            }

            .posts-container {
                gap: 28px;
                padding: 24px 20px;
            }

            .card {
                width: min(100%, 420px);
                height: 280px;
            }
        }
    </style>

    @php
        $bgAvatar =
            auth()->check() && auth()->user()->avatar ? auth()->user()->avatar : asset('images/default-avatar.svg');
    @endphp

    <div class="posts-page">
        <div class="posts-backdrop" style="background-image: url('{{ $bgAvatar }}');"></div>
        @auth
            <div class="posts-toolbar">
                <a href="{{ route('posts.create') }}" class="posts-create-button">Create post+</a>

                @if (\Illuminate\Support\Facades\Route::has('posts.drafts'))
                    <a href="{{ route('posts.drafts') }}" class="posts-create-button posts-drafts-button">
                        Drafts
                        @isset($draftsCount)
                            <span class="posts-drafts-count">{{ $draftsCount }}</span>
                        @endisset
                    </a>
                @endif
            </div>
        @endauth

        @php
            $categoryList = \Illuminate\Support\Facades\DB::table('categories')
                ->orderBy('name')
                ->get(['name', 'slug']);
            $activeCategory = request('category');
            $activeTag = request('tag');
            $activeTagName = $activeTag
                ? \Illuminate\Support\Facades\DB::table('tags')->where('slug', $activeTag)->value('name')
                : null;

            // Категории и теги для всех постов страницы — двумя запросами, без N+1
            $pageItems = method_exists($posts, 'items') ? $posts->items() : $posts;
            $pageIds = collect($pageItems)->pluck('id');
            $categoryNames = \Illuminate\Support\Facades\DB::table('categories')->pluck('name', 'id');
            $tagsByPost = $pageIds->isEmpty()
                ? collect()
                : \Illuminate\Support\Facades\DB::table('post_tag')
                    ->join('tags', 'tags.id', '=', 'post_tag.tag_id')
                    ->whereIn('post_tag.post_id', $pageIds)
                    ->orderBy('post_tag.id')
                    ->get(['post_tag.post_id', 'tags.name'])
                    ->groupBy('post_id');
        @endphp

        <nav class="posts-filters" aria-label="Post categories">
            <a href="{{ route('posts.index', array_filter(['tag' => $activeTag])) }}"
                class="posts-filter {{ $activeCategory ? '' : 'is-active' }}">All</a>

            @foreach ($categoryList as $category)
                <a href="{{ route('posts.index', array_filter(['category' => $category->slug, 'tag' => $activeTag])) }}"
                    class="posts-filter {{ $activeCategory === $category->slug ? 'is-active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </nav>

        @if ($activeTag)
            <div class="posts-active-tag">
                <span>Tag:</span>
                <strong>#{{ $activeTagName ?? $activeTag }}</strong>
                <a href="{{ route('posts.index', array_filter(['category' => $activeCategory])) }}"
                    aria-label="Clear tag filter">×</a>
            </div>
        @endif

        <div class="posts-container">
            @foreach ($posts as $post)
                @php
                    $cardCategory = $categoryNames[$post->category_id ?? 0] ?? null;
                    $cardTags = collect($tagsByPost[$post->id] ?? [])
                        ->pluck('name')
                        ->take(3);
                @endphp
                <a href="{{ route('posts.show', $post->id) }}" class="post-card">
                    <div class="card">
                        @if ($cardCategory)
                            <span class="card-category">{{ $cardCategory }}</span>
                        @endif

                        @if ($post->image)
                            <img src="{{ asset($post->image) }}" class="card-img" alt="{{ $post->title }}">
                        @else
                            <img src="{{ asset('images/default.png') }}" class="card-img" alt="{{ $post->title }}">
                        @endif

                        <div class="title2">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>

                            @if ($cardTags->isNotEmpty())
                                <div class="card-tags">
                                    @foreach ($cardTags as $tag)
                                        <span>#{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if (method_exists($posts, 'links'))
            <div class="posts-pagination">
                {{ $posts->withQueryString()->onEachSide(1)->links('partials.pagination') }}
            </div>
        @endif
    </div>

@endsection
