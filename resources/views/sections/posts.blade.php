<style>
    .posts-section {
        padding: 180px 24px 180px;
    }

    .posts-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 36px;
        max-width: 1440px;
        margin: 0 auto;
    }

    .card {
        display: flex;
        position: relative;
        flex-direction: column;
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.04);
        box-shadow: var(--shadow-soft);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.58) saturate(0.68);
        transition: transform 0.6s ease, filter 0.4s ease;
    }

    .title2 {
        position: absolute;
        bottom: 22px;
        left: 22px;
        right: 22px;
        color: white;
        z-index: 2;
    }

    .title2 h3 {
        margin: 0;
        font-size: 19px;
        transition: all 0.4s ease;
    }

    .title2 p {
        max-height: 0;
        opacity: 0;
        transform: translateY(10px);
        overflow: hidden;
        transition: all 0.4s ease;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.5;
    }

    .card:hover .title2 h3 {
        transform: translateY(-10px);
    }

    .card:hover .title2 p {
        max-height: 120px;
        opacity: 1;
        transform: translateY(0);
        margin-top: 12px;
    }

    .card:hover .card-img {
        transform: scale(1.04);
        filter: brightness(0.62) saturate(0.74);
    }

    .card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.72), rgba(0, 0, 0, 0.12) 55%, rgba(0, 0, 0, 0.08));
        opacity: 0.88;
        transition: 0.3s;
    }

    .card:hover::after {
        opacity: 1;
    }

    .post-card {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 900px) {
        .posts-section {
            padding: 120px 20px;
        }
    }

    .posts-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 16px;
        max-width: 1440px;
        margin: 0 auto 32px;
    }

    .posts-head-title {
        margin: 0;
        font-size: clamp(24px, 4vw, 36px);
        color: var(--text-main);
    }

    .posts-head-link {
        color: var(--text-main);
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        opacity: 0.85;
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .posts-head-link:hover {
        opacity: 1;
        text-decoration: underline;
        text-underline-offset: 6px;
    }

    .posts-head-link span {
        display: inline-block;
        transition: transform 0.25s ease;
    }

    .posts-head-link:hover span {
        transform: translateX(4px);
    }
</style>

@php
    // Сколько постов показывать на главной
    $homeLimit = 6;

    $isHome = request()->routeIs('home');
    $visiblePosts = $isHome ? $posts->take($homeLimit) : $posts;
@endphp

<section class="posts-section" id="home-posts">
    @if ($isHome)
        <div class="posts-head">
            <h2 class="posts-head-title">Latest posts</h2>
            <a href="{{ route('posts.index') }}" class="posts-head-link">See all <span aria-hidden="true">&rarr;</span></a>
        </div>
    @endif

    <div class="posts-container">
        @foreach ($visiblePosts as $post)
            <a href="{{ route('posts.show', $post->id) }}" class="post-card">
                <div class="card">
                    @if ($post->image)
                        <img src="{{ $post->image }}" class="card-img" alt="{{ $post->title }}">
                    @else
                        <img src="{{ asset('images/default.png') }}" class="card-img" alt="{{ $post->title }}">
                    @endif

                    <div class="title2">
                        <h3>{{ $post->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
