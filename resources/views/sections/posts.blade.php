<style>
    .posts-section {
        padding: 180px 24px 180px;
    }

    .posts-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 36px;
        max-width: 1280px;
        margin: 0 auto;
    }

    .card {
        display: flex;
        position: relative;
        flex-direction: column;
        width: 100%;
        min-height: 300px;
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
</style>

<section class="posts-section" id="home-posts">
    <div class="posts-container">
        @foreach ($posts as $post)
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
