@php
    $homeUrl = route('home');
    $postsAnchor = request()->routeIs('home') ? '#home-posts' : $homeUrl . '#home-posts';
    $aboutAnchor = request()->routeIs('home') ? '#home-about' : $homeUrl . '#home-about';
    $createAnchor = request()->routeIs('home') ? '#home-create-post' : $homeUrl . '#home-create-post';
@endphp

<footer class="footer" id="home-footer">
    <div class="footer-inner">
        <div class="footer-left">
            <h2>MakeABlog</h2>
            <p>Create. Share. Feel.</p>
            <span>A simple platform for your thoughts and stories.</span>
        </div>

        <div class="footer-socials">
            <h3>Connect with me</h3>
            <ul>
                <li><a href="https://t.me/myavk88" target="_blank">Telegram</a></li>
                <li><a href="https://www.instagram.com/myavk88?igsh=MXN5NnNlcnluajJ5cg==" target="_blank">Instagram</a>
                </li>
                <li><a href="https://www.linkedin.com/in/sabina-aznabaeva-630668357?utm_source=share_via&utm_content=profile&utm_medium=member_android"
                        target="_blank">LinkedIn</a></li>
                <li><a href="https://github.com/myavkmrazi" target="_blank">GitHub</a></li>
            </ul>
        </div>

        <div class="footer-right">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ $postsAnchor }}">Posts</a></li>
                <li><a href="{{ $aboutAnchor }}">About</a></li>
                <li><a href="{{ $createAnchor }}">Create</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        © 2026 MakeABlog. All rights reserved.
    </div>
</footer>

<style>
    footer.footer {
        width: 100%;
        background: #181818;
        color: #fff;
        padding: 72px 0 32px;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 48px;
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 12px;
        color: #aaa;
    }

    .footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 40px;
    }

    .footer-left h2 {
        font-size: 24px;
        margin-bottom: 14px;
    }

    .footer-left p {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .footer-left span {
        font-size: 12px;
        color: #aaa;
    }

    /* Стили для текстового блока соцсетей */
    .footer-socials h3 {
        font-size: 16px;
        margin-bottom: 14px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .footer-socials ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 0;
        margin: 0;
    }

    .footer-socials a {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        opacity: 0.88;
        transition: color 0.25s ease, opacity 0.25s ease;
    }

    /* При наведении ссылка плавно окрашивается в твой фирменный мятный цвет */
    .footer-socials a:hover {
        opacity: 1;
        color: #00FFCC;
    }

    .footer-right ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .footer-right a {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        opacity: 0.88;
        transition: opacity 0.25s ease;
    }

    .footer-right a:hover {
        opacity: 1;
    }

    @media (max-width: 900px) {
        .footer-inner {
            flex-direction: column;
            gap: 32px;
        }
    }
</style>
