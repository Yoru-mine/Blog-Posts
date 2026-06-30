<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>
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

        [data-theme="lighter"] {
            --page-bg: #8f8383;
            --surface: #a09494;
            --surface-soft: rgba(0, 0, 0, 0.06);
            --surface-line: rgba(0, 0, 0, 0.1);
            --text-main: #2a2424;
            --text-muted: #6b5e5e;
            --shadow-soft: 0 22px 80px rgba(0, 0, 0, 0.12);
        }

        * {
            transition: background-color 0.5s ease,
                color 0.4s ease,
                border-color 0.4s ease,
                box-shadow 0.4s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            overflow-x: hidden;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'IBM Plex Mono';
            background-color: var(--page-bg);
            color: var(--text-main);
            width: 100%;
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
            background-color: rgba(26, 26, 26, 0.55);
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
            font-size: clamp(20px, 5vw, 32px);
            font-weight: bold;
            letter-spacing: 0.02em;
            color: var(--text-main);
            text-decoration: none;
            line-height: 1;
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

        .btn-search {
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 18px;
            cursor: pointer;
            opacity: 0.92;
            transition: opacity 0.25s ease, transform 0.25s ease;
            padding: 0;
            line-height: 1;
        }

        .btn-theme-toggle {
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 18px;
            cursor: pointer;
            opacity: 0.92;
            transition: opacity 0.25s ease, transform 0.25s ease;
            padding: 0;
            line-height: 1;
            margin-left: 8px;
        }

        .btn-theme-toggle:hover {
            opacity: 1;
            transform: scale(1.1);
        }


        .btn-search:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .burger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--text-main);
            transition: transform .25s ease, opacity .2s ease;
        }

        .burger.is-open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .burger.is-open span:nth-child(2) {
            opacity: 0;
        }

        .burger.is-open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        @media (max-width: 900px) {
            .main-menu {
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                padding: 0 20px !important;
                gap: 0 !important;
                align-items: center;
                height: 64px;
            }

            .menu-left {
                flex-direction: row !important;
                gap: 12px !important;
                align-items: center;
            }

            .burger {
                display: flex;
                margin-left: auto;
                flex-shrink: 0;
            }

            .menu-links {
                display: flex;
                position: fixed;
                top: 0;
                right: 0;
                width: min(78vw, 320px);
                height: 100dvh;
                flex-direction: column;
                gap: 0 !important;
                margin: 0;
                padding: 96px 24px 24px;
                background: rgba(20, 20, 20, 0.97);
                backdrop-filter: blur(14px);
                border-left: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: -16px 0 48px rgba(0, 0, 0, 0.35);
                overflow-y: auto;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                z-index: 1050;
            }

            .menu-links.is-open {
                transform: translateX(0);
            }

            .menu-links li {
                width: 100%;
            }

            .menu-links a,
            .btn-logout,
            .btn-search,
            .btn-theme-toggle {
                display: block;
                width: 100%;
                padding: 12px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.06);
                font-size: 16px !important;
            }

            .menu-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                z-index: 990;
            }

            .menu-backdrop.is-open {
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>
</head>

<body>
    <div class="menu-backdrop" id="menu-backdrop"></div>
    @php
        $homeUrl = route('home');
        $postsAnchor = route('posts.index');
        $aboutAnchor = request()->routeIs('home') ? '#home-about' : $homeUrl . '#home-about';
        $createAnchor = auth()->check()
            ? route('posts.create')
            : (request()->routeIs('home')
                ? '#home-create-post'
                : $homeUrl . '#home-create-post');
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

        <button class="burger" id="burger" aria-label="Меню">
            <span></span>
            <span></span>
            <span></span>
        </button>






        <ul class="menu-links">
            <li>
                <button class="btn-search" id="open-search" aria-label="Search">
                    <img src="{{ asset('images/search.svg') }}" class="search-icon-menu" alt="Search">
                </button>
                <button class="btn-theme-toggle" id="theme-toggle" aria-label="Toggle theme">
                    <img src="{{ asset('images/theme.svg') }}" class="theme-icon-menu" alt="Theme">
                </button>

            </li>
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
                    <form method="POST" action="{{ route('logout') }}" data-confirm data-confirm-title="Log out?"
                        data-confirm-message="Are you sure you want to log out?" data-confirm-button="Log out">
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
                session('success')
                    ? ['type' => 'success', 'label' => 'Success', 'message' => session('success')]
                    : null,
                session('error') ? ['type' => 'error', 'label' => 'Error', 'message' => session('error')] : null,
            ])
                ->filter()
                ->values();
        @endphp

        @if ($flashMessages->isNotEmpty())
            <div class="flash-stack" data-flash-stack>
                @foreach ($flashMessages as $flash)
                    <div class="flash-message is-{{ $flash['type'] }}" data-flash-message>
                        <span class="flash-label">{{ $flash['label'] }}</span>
                        <div class="flash-copy">{{ $flash['message'] }}</div>
                        <button type="button" class="flash-close" aria-label="Close message"
                            data-flash-close>&times;</button>
                    </div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <div class="confirm-overlay" data-confirm-overlay aria-hidden="true">
        <div class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirm-title"
            aria-describedby="confirm-copy">
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
                        confirmCopy.textContent = form.dataset.confirmMessage ||
                            'This action cannot be undone.';
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
    <!-- SEARCH MODAL -->
    <style>
        .search-overlay {
            position: fixed;
            inset: 0;
            z-index: 1500;
            background: rgba(8, 8, 8, 0.7);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 120px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .search-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        .search-box {
            width: min(580px, calc(100vw - 32px));
            background: rgba(24, 24, 24, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transform: translateY(-10px);
            transition: transform 0.2s ease;
        }

        .search-overlay.is-open .search-box {
            transform: translateY(0);
        }

        .search-input-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .search-icon-modal {
            font-size: 18px;
            opacity: 0.5;
            flex-shrink: 0;
            width: 20px;
            height: 20px;

        }

        .search-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 17px;
            font-family: inherit;
            color: var(--text-main);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .search-hint {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .search-results {
            max-height: 360px;
            overflow-y: auto;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            text-decoration: none;
            color: var(--text-main);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.15s ease;
        }

        .search-result-item:hover,
        .search-result-item.is-active {
            background: rgba(255, 255, 255, 0.06);
        }

        .search-result-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.06);
        }

        .search-result-info {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .search-result-title {
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-title mark {
            background: transparent;
            color: #f2ede2;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .search-result-excerpt {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-empty {
            padding: 28px 20px;
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.38);
        }

        .search-footer {
            padding: 10px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            gap: 16px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
        }

        .search-footer kbd {
            display: inline-block;
            padding: 1px 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-family: inherit;
            font-size: 11px;
            border-radius: 2px;
        }

        .search-icon-menu {
            width: 24.7px;
            height: 24.7px;
            opacity: 0.5;
            filter: brightness(0) invert(1);

        }

        .theme-icon-menu {
            width: 24.7px;
            height: 24.7px;
            opacity: 0.5;
            display: block;
            filter: brightness(0) invert(1);
        }

        .search-spinner {
            display: none;
            justify-content: center;
            padding: 28px 20px;
        }

        .search-spinner.is-loading {
            display: flex;
        }

        .spinner {
            width: 28px;
            height: 28px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: #f4f4f4;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-up {
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 52px;
            height: 52px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(24, 24, 24, 0.88);
            color: #f4f4f4;
            font-size: 26px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease, background 0.2s ease;
            z-index: 1100;
        }

        .btn-up.is-visible {
            opacity: 0.8;
            pointer-events: auto;
            transform: translateY(0);
        }

        .btn-up:hover {
            opacity: 1;
            background: rgba(40, 40, 40, 0.95);
        }
    </style>

    <div class="search-overlay" id="search-overlay" aria-hidden="true">
        <div class="search-box" role="dialog" aria-label="Search posts">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <img src="{{ asset('images/search.svg') }}" class="search-icon-modal" alt="Search Modal">
                </span>
                <input type="text" class="search-input" id="search-input" placeholder="Search posts..."
                    autocomplete="off" aria-label="Search">
                <span class="search-hint">ESC to close</span>
            </div>
            <div class="search-results" id="search-results">
                <div class="search-spinner" id="search-spinner">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="search-footer">
                <span><kbd>↑</kbd> <kbd>↓</kbd> navigate</span>
                <span><kbd>Enter</kbd> open</span>
                <span><kbd>Ctrl K</kbd> toggle</span>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const overlay = document.getElementById('search-overlay');
            const input = document.getElementById('search-input');
            const results = document.getElementById('search-results');
            const openBtn = document.getElementById('open-search');

            let debounceTimer = null;
            let activeIndex = -1;


            const openSearch = () => {
                overlay.classList.add('is-open');
                overlay.setAttribute('aria-hidden', 'false');
                input.focus();
            };

            const closeSearch = () => {
                overlay.classList.remove('is-open');
                overlay.setAttribute('aria-hidden', 'true');
                input.value = '';
                results.innerHTML = '';
                activeIndex = -1;
            };

            openBtn?.addEventListener('click', openSearch);

            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    overlay.classList.contains('is-open') ? closeSearch() : openSearch();
                }
                if (e.key === 'Escape') closeSearch();
            });


            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) closeSearch();
            });


            const highlight = (text, query) => {
                if (!query) return text;
                const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp(`(${escaped})`, 'gi'), '<mark>$1</mark>');
            };


            const renderResults = (posts, query) => {
                activeIndex = -1;

                if (!posts.length) {
                    results.innerHTML =
                        `<p class="search-empty">No posts found for "<strong>${query}</strong>"</p>`;
                    return;
                }

                results.innerHTML = posts.map((post, i) => `
                <a href="${post.url}" class="search-result-item" data-index="${i}">
                    <img src="${post.image}" alt="" class="search-result-img">
                    <div class="search-result-info">
                        <div class="search-result-title">${highlight(post.title, query)}</div>
                        <div class="search-result-excerpt">${post.excerpt}</div>
                    </div>
                </a>
            `).join('');
            };


            input.addEventListener('input', () => {
                const query = input.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    results.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    const spinner = document.getElementById('search-spinner');
                    const resultsContainer = document.getElementById('search-results');

                    resultsContainer.innerHTML = '';
                    spinner?.classList.add('is-loading');

                    try {
                        const res = await fetch(`/posts/search?q=${encodeURIComponent(query)}`, {

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                        });
                        const data = await res.json();
                        renderResults(data, query);
                    } catch (err) {
                        console.error('Search error:', err);
                        results.innerHTML = `<p class="search-empty">Search failed. Try again.</p>`;
                    } finally {
                        spinner?.classList.remove('is-loading');
                    }
                }, 300);
            });


            input.addEventListener('keydown', (e) => {
                const items = results.querySelectorAll('.search-result-item');
                if (!items.length) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, 0);
                } else if (e.key === 'Enter' && activeIndex >= 0) {
                    items[activeIndex]?.click();
                    return;
                } else {
                    return;
                }

                items.forEach((item, i) => item.classList.toggle('is-active', i === activeIndex));
            });
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('theme-toggle');

            if (localStorage.getItem('theme') === 'lighter') {
                document.documentElement.setAttribute('data-theme', 'lighter');
            }

            toggle?.addEventListener('click', () => {
                const html = document.documentElement;
                const isLighter = html.getAttribute('data-theme') === 'lighter';

                if (isLighter) {
                    html.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'dark');
                } else {
                    html.setAttribute('data-theme', 'lighter');
                    localStorage.setItem('theme', 'lighter');
                }
            });
        });
    </script>

    <script src="{{ asset('js/reactions.js') }}"></script>

    <button class="btn-up" id="btn-up" aria-label="Scroll to top">
        ↑
    </button>

    <script>
        (() => {
            const btnUp = document.getElementById('btn-up');
            if (btnUp) {
                window.addEventListener('scroll', () => {
                    btnUp.classList.toggle('is-visible', window.scrollY > 300);
                });
                btnUp.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        })();
        const burger = document.getElementById('burger');
        const menuLinks = document.querySelector('.menu-links');
        const menuBackdrop = document.getElementById('menu-backdrop');

        const closeMenu = () => {
            burger?.classList.remove('is-open');
            menuLinks?.classList.remove('is-open');
            menuBackdrop?.classList.remove('is-open');
            document.body.style.overflow = '';
        };

        burger?.addEventListener('click', () => {
            const willOpen = !menuLinks?.classList.contains('is-open');
            burger.classList.toggle('is-open', willOpen);
            menuLinks?.classList.toggle('is-open', willOpen);
            menuBackdrop?.classList.toggle('is-open', willOpen);
            document.body.style.overflow = willOpen ? 'hidden' : '';
        });

        menuBackdrop?.addEventListener('click', closeMenu);
        menuLinks?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    </script>


</body>

</html>
