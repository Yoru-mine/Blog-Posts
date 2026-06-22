<section class="hero" id="home-hero">
    <div class="hero-content">
        <h1 class="title-hero">Make your blog</h1>
        <a href="{{ route('posts.create') }}" class="btn">Create post</a>
    </div>
</section>

<style>
    .title-hero {
        font-size: 68px;
        margin: 0;
        line-height: 1;
    }

    .hero {
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        text-align: center;
        position: relative;
        isolation: isolate;
        background-image: url('{{ asset('images/eye 1.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 100px 24px 40px;
    }

    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(to bottom, rgba(15, 15, 15, 0.34), rgba(15, 15, 15, 0.1) 28%, rgba(15, 15, 15, 0.22)),
            radial-gradient(circle at center, rgba(255, 255, 255, 0.05), transparent 55%);
        z-index: -1;
    }

    .hero-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        max-width: 900px;
    }

    .hero-content h1 {
        letter-spacing: 4px;
        color: white;
        mix-blend-mode: difference;
        text-wrap: balance;
    }

    .btn {
        display: inline-block;
        padding: 26px 45px;
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        text-decoration: none;
        margin-top: 137px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: transform 0.25s ease, background-color 0.25s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        background-color: rgba(0, 0, 0, 0.78);
    }

    @media (max-width: 900px) {
        .title-hero {
            font-size: 46px;
        }

        .btn {
            margin-top: 72px;
            padding: 18px 28px;
        }
    }
</style>
