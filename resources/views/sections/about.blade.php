<section class="about-section" id="home-about">
    <style>
        .about-section {
            padding-bottom: 170px;
        }

        .banner-about {
            position: relative;
            width: 100%;
            height: 527px;
            background-image: url('{{ asset('images/es.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .banner-about::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.08) 40%, rgba(0, 0, 0, 0.4));
        }

        .text-overlay {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 900px;
            padding: 0 24px;
        }

        .text-overlay h1 {
            font-size: 58px;
            margin: 0;
        }

        .text-overlay h4 {
            font-size: 24px;
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.88);
        }

        .title-about-h1 {
            font-size: 48px;
        }

        .p-about {
            font-size: 22px;
            margin-top: 42px;
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
        }

        .content-about {
            width: 878px;
            max-width: calc(100% - 40px);
            margin: 140px auto 0;
            text-align: center;
            color: white;
        }

        .cards-about-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(280px, 1fr));
            max-width: 1280px;
            gap: 34px;
            margin: 160px auto 0;
            padding: 0 24px;
        }

        .card-about {
            display: flex;
            position: relative;
            flex-direction: column;
            min-height: 382px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.04);
            box-shadow: var(--shadow-soft);
        }

        .about-img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.82);
        }

        .content-card-about {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            color: white;
        }

        .content-card-about h1 {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .content-card-about p {
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .cards-about-container {
                grid-template-columns: 1fr;
            }

            .text-overlay h1,
            .title-about-h1 {
                font-size: 40px;
            }
        }
    </style>

    <div class="banner-about">
        <div class="text-overlay">
            <h1>About this site</h1>
            <h4>Create. Share. Feel.</h4>
        </div>
    </div>
    <div class="content-about">
        <h1 class="title-about-h1">What is MakeABlog?</h1>
        <p class="p-about">MakeABlog is a simple platform where anyone can create posts, share their thoughts, and build
            a personal blog.<br>
            No limits. Just your creativity.</p>
    </div>

    <div class="cards-about-container">
        <div class="card-about">
            <img src="{{ asset('images/klava.png') }}" class="about-img" alt="Create posts">
            <div class="content-card-about">
                <h1>Create posts</h1>
                <p>Write and publish your own stories</p>
            </div>
        </div>

        <div class="card-about">
            <img src="{{ asset('images/images.png') }}" class="about-img" alt="Add images">
            <div class="content-card-about">
                <h1>Add images</h1>
                <p>Make your blog visual</p>
            </div>
        </div>

        <div class="card-about">
            <img src="{{ asset('images/klava2.png') }}" class="about-img" alt="Minimal design">
            <div class="content-card-about">
                <h1>Minimal design</h1>
                <p>Focus on content</p>
            </div>
        </div>

        <div class="card-about">
            <img src="{{ asset('images/knopka.png') }}" class="about-img" alt="Fast and simple">
            <div class="content-card-about">
                <h1>Fast & simple</h1>
                <p>No complicated setup</p>
            </div>
        </div>
    </div>
</section>
