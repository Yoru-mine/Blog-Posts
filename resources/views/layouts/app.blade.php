<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" initial-scale="1">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        :root {
            --page-bg: #2b2b2b;
            --surface: #323232;
            --surface-soft: rgba(255, 255, 255, 0.08);
            --surface-line: rgba(255, 255, 255, 0.14);
            --text-main: #f4f4f4;
            --text-muted: #b5b5b5;
            --shadow-soft: 0 22px 80px rgba(0, 0, 0, 0.28);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'IBM Plex Mono';
            background-color: var(--page-bg);
            color: var(--text-main);
        }

        [id^="home-"] {
            scroll-margin-top: 108px;
        }

        .main-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 48px;
            background-color: rgba(26, 26, 26, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 57px;
        }

        .brand-title {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 0.02em;
            color: var(--text-main);
            text-decoration: none;
        }

        .btn-back {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 24px;
            line-height: 1;
            opacity: 0.92;
            transition: opacity 0.25s ease, color 0.25s ease;
        }

        .btn-back:hover {
            color: #fff;
            opacity: 1;
        }

        .menu-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 28px;
            margin-left: -6px;
        }

        .menu-links a,
        .btn-profile {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            opacity: 0.92;
            transition: opacity 0.25s ease, color 0.25s ease, transform 0.25s ease;
        }

        .menu-links a:hover,
        .btn-profile:hover {
            color: #fff;
            opacity: 1;
        }

        .btn-profile:hover {
            transform: translateY(-1px);
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 0;
            color: var(--text-main);
        }

        .flash-stack {
            position: fixed;
            top: 96px;
            right: 24px;
            z-index: 1300;
            display: grid;
            gap: 12px;
            width: min(380px, calc(100vw - 28px));
        }

        .flash-message {
            position: relative;
            padding: 16px 18px 18px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(24, 24, 24, 0.92);
            backdrop-filter: blur(14px);
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.24);
            overflow: hidden;
            animation: flash-enter 0.34s ease;
        }

        .flash-message::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.16);
            transform-origin: left center;
            animation: flash-timer 4.8s linear forwards;
        }

        .flash-message.is-success::after {
            background: rgba(212, 237, 196, 0.9);
        }

        .flash-message.is-error::after {
            background: rgba(241, 167, 167, 0.95);
        }

        .flash-label {
            display: inline-block;
            margin-bottom: 8px;
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.52);
        }

        .flash-copy {
            font-size: 14px;
            line-height: 1.7;
            color: var(--text-main);
        }

        .flash-message.is-error .flash-copy {
            color: #ffd9d9;
        }

        .flash-message.is-success .flash-copy {
            color: #eef5e6;
        }

        .flash-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.68);
            font: inherit;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .flash-close:hover {
            opacity: 1;
            transform: scale(1.06);
        }

        .flash-message.is-leaving {
            animation: flash-leave 0.24s ease forwards;
        }

        .confirm-overlay {
            position: fixed;
            inset: 0;
            z-index: 1400;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(8, 8, 8, 0.58);
            backdrop-filter: blur(10px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .confirm-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .confirm-dialog {
            width: min(460px, 100%);
            padding: 26px 24px 24px;
            background: rgba(24, 24, 24, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.34);
            transform: translateY(8px) scale(0.98);
            transition: transform 0.2s ease;
        }

        .confirm-overlay.is-open .confirm-dialog {
            transform: translateY(0) scale(1);
        }

        .confirm-kicker {
            display: inline-block;
            margin-bottom: 12px;
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
        }

        .confirm-title {
            margin: 0 0 12px;
            font-size: 28px;
            line-height: 1.15;
        }

        .confirm-copy {
            margin: 0;
            font-size: 15px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.74);
        }

        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .confirm-button,
        .confirm-button-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-main);
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .confirm-button-danger {
            background: rgba(255, 214, 214, 0.92);
            color: #3a1b1b;
            border-color: transparent;
        }

        .confirm-button:hover,
        .confirm-button-danger:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .pagination-shell {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            list-style: none;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            min-height: 42px;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-main);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease, background 0.2s ease;
        }

        .pagination a:hover {
            transform: translateY(-1px);
            opacity: 0.96;
            background: rgba(255, 255, 255, 0.1);
        }

        .pagination .active span {
            background: #f2ede2;
            color: #1E1E1E;
            border-color: transparent;
        }

        .pagination .disabled span {
            opacity: 0.42;
        }

        @keyframes flash-enter {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes flash-leave {
            to {
                opacity: 0;
                transform: translateY(-8px);
            }
        }

        @keyframes flash-timer {
            to {
                transform: scaleX(0);
            }
        }

        @media (max-width: 900px) {
            .main-menu {
                padding: 18px 20px;
                flex-direction: column;
                gap: 14px;
            }

            .menu-left {
                gap: 18px;
                flex-direction: column;
            }

            .menu-links {
                gap: 16px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .flash-stack {
                top: 108px;
                right: 14px;
                left: 14px;
                width: auto;
            }
        }

        .btn-logout {
            background: none;
            border: none;
            color: var(--text-main);
            font-family: inherit;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            opacity: 0.92;
            transition: opacity 0.25s ease, color 0.25s ease;
        }

        .btn-logout:hover {
            color: #fff;
            opacity: 1;
        }
    </style>
</head>

<body>
    @php
        $homeUrl = route('home');
        $postsAnchor = request()->routeIs('home') ? '#home-posts' : $homeUrl . '#home-posts';
        $aboutAnchor = request()->routeIs('home') ? '#home-about' : $homeUrl . '#home-about';
        $createAnchor = request()->routeIs('home') ? '#home-create-post' : $homeUrl . '#home-create-post';
        $footerAnchor = request()->routeIs('home') ? '#home-footer' : $homeUrl . '#home-footer';
    @endphp

    <nav class="main-menu">
        <div class="menu-left">
            <a class="brand-title" href="{{ route('home') }}">MakeABlog</a>
            @if (request()->routeIs('posts.show'))
                <a href="{{ route('posts.index') }}" class="btn-back" aria-label="Back to posts">&larr; Back to
                    posts</a>
            @endif
        </div>

        <ul class="menu-links">
            <li><a href="{{ $postsAnchor }}">Posts</a></li>
            <li><a href="{{ $createAnchor }}">Create post</a></li>
            <li><a href="{{ $aboutAnchor }}">About</a></li>
            <li><a href="{{ $footerAnchor }}">Contacts</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('profile.edit') }}" class="btn-profile" aria-label="Profile">Profile</a></li>
                @if (auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.panel') }}">Admin panel</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Log out</button>
                    </form>
                </li>
            @endauth
            @guest
                <li><a href="{{ route('login') }}">Log in</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endguest
        </ul>
    </nav>

    <div class="content">
        @php
            $flashMessages = collect([
                session('success') ? ['type' => 'success', 'label' => 'Success', 'message' => session('success')] : null,
                session('error') ? ['type' => 'error', 'label' => 'Error', 'message' => session('error')] : null,
            ])->filter()->values();
        @endphp

        @if ($flashMessages->isNotEmpty())
            <div class="flash-stack" data-flash-stack>
                @foreach ($flashMessages as $flash)
                    <div class="flash-message is-{{ $flash['type'] }}" data-flash-message>
                        <span class="flash-label">{{ $flash['label'] }}</span>
                        <div class="flash-copy">{{ $flash['message'] }}</div>
                        <button type="button" class="flash-close" aria-label="Close message" data-flash-close>&times;</button>
                    </div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <div class="confirm-overlay" data-confirm-overlay aria-hidden="true">
        <div class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-copy">
            <span class="confirm-kicker">Confirmation</span>
            <h2 class="confirm-title" id="confirm-title">Delete item?</h2>
            <p class="confirm-copy" id="confirm-copy">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button type="button" class="confirm-button" data-confirm-cancel>Cancel</button>
                <button type="button" class="confirm-button-danger" data-confirm-submit>Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const removeFlash = (flash) => {
                if (!flash || flash.classList.contains('is-leaving')) {
                    return;
                }

                flash.classList.add('is-leaving');
                window.setTimeout(() => flash.remove(), 240);
            };

            document.querySelectorAll('[data-flash-message]').forEach((flash) => {
                const closeButton = flash.querySelector('[data-flash-close]');
                closeButton?.addEventListener('click', () => removeFlash(flash));
                window.setTimeout(() => removeFlash(flash), 4800);
            });

            const confirmOverlay = document.querySelector('[data-confirm-overlay]');
            const confirmTitle = document.getElementById('confirm-title');
            const confirmCopy = document.getElementById('confirm-copy');
            const confirmSubmit = document.querySelector('[data-confirm-submit]');
            const confirmCancel = document.querySelector('[data-confirm-cancel]');
            let pendingForm = null;

            const closeConfirm = () => {
                pendingForm = null;
                confirmOverlay?.classList.remove('is-open');
                confirmOverlay?.setAttribute('aria-hidden', 'true');
            };

            document.querySelectorAll('form[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    pendingForm = form;

                    if (confirmTitle) {
                        confirmTitle.textContent = form.dataset.confirmTitle || 'Delete item?';
                    }

                    if (confirmCopy) {
                        confirmCopy.textContent = form.dataset.confirmMessage || 'This action cannot be undone.';
                    }

                    if (confirmSubmit) {
                        confirmSubmit.textContent = form.dataset.confirmButton || 'Delete';
                    }

                    confirmOverlay?.classList.add('is-open');
                    confirmOverlay?.setAttribute('aria-hidden', 'false');
                });
            });

            confirmCancel?.addEventListener('click', closeConfirm);

            confirmOverlay?.addEventListener('click', (event) => {
                if (event.target === confirmOverlay) {
                    closeConfirm();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && confirmOverlay?.classList.contains('is-open')) {
                    closeConfirm();
                }
            });

            confirmSubmit?.addEventListener('click', () => {
                if (pendingForm) {
                    const form = pendingForm;
                    closeConfirm();

                    if (form.dataset.confirmAjax === 'true') {
                        form.dispatchEvent(new CustomEvent('confirmed-submit', {
                            bubbles: true,
                        }));
                        return;
                    }

                    HTMLFormElement.prototype.submit.call(form);
                }
            });

            const isHomePage = @json(request()->routeIs('home'));
            const homeHashLinks = document.querySelectorAll('a[href*="#home-"]');

            const scrollToHashTarget = (hash, pushState = false) => {
                if (!hash || !hash.startsWith('#home-')) {
                    return;
                }

                const target = document.querySelector(hash);

                if (!target) {
                    return;
                }

                window.requestAnimationFrame(() => {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });

                    if (pushState) {
                        window.history.pushState(null, '', hash);
                    }
                });
            };

            homeHashLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href') || '';
                    const hashIndex = href.indexOf('#home-');

                    if (hashIndex === -1) {
                        return;
                    }

                    const hash = href.slice(hashIndex);

                    if (isHomePage) {
                        event.preventDefault();
                        scrollToHashTarget(hash, true);
                    }
                });
            });

            if (window.location.hash.startsWith('#home-')) {
                const hash = window.location.hash;

                window.scrollTo({
                    top: 0,
                    left: 0,
                    behavior: 'auto',
                });

                window.setTimeout(() => {
                    scrollToHashTarget(hash, false);
                }, 60);
            }
        });
    </script>
</body>

</html>
