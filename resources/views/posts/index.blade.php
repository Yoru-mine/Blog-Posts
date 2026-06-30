@extends('layouts.app')

@section('title', 'Posts')

@section('content')

    <style>
        body {
            margin: 0;
            background: #2D2D2D;
        }

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
            filter: blur(30px) brightness(0.3);
            transform: scale(1.1);
            opacity: 0.5;
            z-index: 0;
            pointer-events: none;
        }

        .posts-container,
        .posts-pagination {
            position: relative;
            z-index: 1;
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
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.55) saturate(0.7);
        }

        .title2 {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            color: white;
            z-index: 2;
        }

        .title2 h3 {
            margin: 0;
            transition: all 0.4s ease;
        }

        .title2 p {
            max-height: 0;
            opacity: 0;
            transform: translateY(10px);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .card:hover .title2 h3 {
            transform: translateY(-10px);
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
            auth()->check() && auth()->user()->avatar
                ? asset('storage/' . auth()->user()->avatar)
                : asset('images/default-avatar.svg');
    @endphp

    <div class="posts-page">
        <div class="posts-backdrop" style="background-image: url('{{ $bgAvatar }}');"></div>
        <div class="posts-container">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post->id) }}" class="post-card">
                    <div class="card">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img" alt="{{ $post->title }}">
                        @else
                            <img src="/images/default.png" class="card-img" alt="{{ $post->title }}">
                        @endif

                        <div class="title2">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($post->content, 100) }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if (method_exists($posts, 'links'))
            <div class="posts-pagination">
                {{ $posts->onEachSide(1)->links('partials.pagination') }}
            </div>
        @endif
    </div>

@endsection
