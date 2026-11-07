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
        /* Lenis: обязательные стили (native smooth scroll конфликтует с Lenis) */
        html.lenis,
        html.lenis body {
            height: auto;
        }

        .lenis.lenis-smooth {
            scroll-behavior: auto !important;
        }

        .lenis.lenis-smooth [data-lenis-prevent] {
            overscroll-behavior: contain;
        }

        .lenis.lenis-stopped {
            overflow: hidden;
        }

        .lenis.lenis-smooth iframe {
            pointer-events: none;
        }

        /* ===== Палитра и токены: все цвета страниц берутся отсюда ===== */
        :root {
            /* фон и поверхности: нейтральный серый, почти чёрный, без цветного оттенка */
            --page-bg: #0b0b0b;
            --surface: #121212;
            --surface-2: #181818;
            --surface-3: #202020;
            --surface-soft: rgba(255, 255, 255, 0.05);
            --surface-hover: rgba(255, 255, 255, 0.09);
            --surface-line: rgba(255, 255, 255, 0.06);
            /* только для совместимости со старыми страницами */

            /* текст */
            --text-main: #e6e6e6;
            --text-muted: #a0a0a0;

            /* приглушённый акцент (молочно-серый), опасное действие, успех */
            --accent: #d4d2cc;
            --accent-hover: #e3e1dc;
            --accent-rgb: 212, 210, 204;
            --accent-contrast: #161616;
            --danger: #cc7f78;
            --danger-hover: #d8928b;
            --danger-rgb: 204, 127, 120;
            --danger-contrast: #1c0e0c;
            --success: #92ad92;
            --success-rgb: 146, 173, 146;

            /* полупрозрачные слои */
            --nav-bg: rgba(11, 11, 11, 0.72);
            --nav-bar-bg: rgba(14, 14, 14, 0.94);
            --overlay-bg: rgba(0, 0, 0, 0.7);

            /* тени вместо границ */
            --shadow-soft: 0 22px 80px rgba(0, 0, 0, 0.6);
            --shadow-raise: 0 12px 32px rgba(0, 0, 0, 0.55);
            --shadow-nav: 0 12px 36px rgba(0, 0, 0, 0.45);

            /* мягкое свечение от акцента (только text-shadow у текста) */
            --glow-faint: 0 0 8px rgba(var(--accent-rgb), 0.1);
            --glow: 0 0 12px rgba(var(--accent-rgb), 0.28);
            --glow-hover: 0 0 8px rgba(var(--accent-rgb), 0.4), 0 0 20px rgba(var(--accent-rgb), 0.45);
            --box-glow: 0 0 22px rgba(var(--accent-rgb), 0.16);

            /* углы всегда острые */
            --btn-h: 44px;
            --btn-pad: 0 20px;
            --btn-radius: 0;
            --card-radius: 0;

            /* фон «пустоты»: лёгкая аура сверху, глубина по краям, статичное зерно */
            --grain: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 .05 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
            --void: radial-gradient(110% 65% at 50% -12%, rgba(var(--accent-rgb), 0.05) 0%, transparent 62%),
                radial-gradient(70% 50% at 100% 105%, rgba(var(--accent-rgb), 0.03) 0%, transparent 70%),
                radial-gradient(150% 105% at 50% 45%, transparent 50%, rgba(0, 0, 0, 0.75) 100%);

            --icon-filter: brightness(0) invert(1);
        }

        [data-theme="lighter"] {
            --page-bg: #cfcfcf;
            --surface: #dcdcdc;
            --surface-2: #e6e6e6;
            --surface-3: #efefef;
            --surface-soft: rgba(0, 0, 0, 0.06);
            --surface-hover: rgba(0, 0, 0, 0.1);
            --surface-line: rgba(0, 0, 0, 0.08);

            --text-main: #151515;
            --text-muted: #4a4a4a;

            --accent: #3f3f3f;
            --accent-hover: #2a2a2a;
            --accent-rgb: 63, 63, 63;
            --accent-contrast: #f2f2f2;
            --danger: #9a3b35;
            --danger-hover: #82302b;
            --danger-rgb: 154, 59, 53;
            --danger-contrast: #f8eceb;
            --success: #4a6b4a;
            --success-rgb: 74, 107, 74;

            --nav-bg: rgba(207, 207, 207, 0.78);
            --nav-bar-bg: rgba(220, 220, 220, 0.96);
            --overlay-bg: rgba(20, 20, 20, 0.45);

            --shadow-soft: 0 22px 80px rgba(0, 0, 0, 0.2);
            --shadow-raise: 0 12px 32px rgba(0, 0, 0, 0.18);
            --shadow-nav: 0 8px 28px rgba(0, 0, 0, 0.14);

            --glow-faint: 0 0 8px rgba(var(--accent-rgb), 0.06);
            --glow: 0 0 10px rgba(var(--accent-rgb), 0.16);
            --glow-hover: 0 0 8px rgba(var(--accent-rgb), 0.24), 0 0 18px rgba(var(--accent-rgb), 0.28);
            --box-glow: 0 0 20px rgba(var(--accent-rgb), 0.14);

            --grain: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 .07 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
            --void: radial-gradient(110% 65% at 50% -12%, rgba(var(--accent-rgb), 0.04) 0%, transparent 62%),
                radial-gradient(70% 50% at 100% 105%, rgba(var(--accent-rgb), 0.03) 0%, transparent 70%),
                radial-gradient(150% 105% at 50% 45%, transparent 55%, rgba(0, 0, 0, 0.14) 100%);

            --icon-filter: brightness(0);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.5s ease,
                color 0.4s ease,
                box-shadow 0.4s ease,
                text-shadow 0.4s ease;
        }

        html {
            overflow-x: hidden;
        }

        body {
            overflow-x: clip;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'IBM Plex Mono';
            font-size: 16px;
            background-color: var(--page-bg);
            background-image: var(--void), var(--grain);
            background-attachment: fixed;
            color: var(--text-main);
            width: 100%;
        }

        [id^="home-"] {
            scroll-margin-top: 108px;
        }

        /* Видимый фокус с клавиатуры для всех интерактивных элементов */
        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 3px;
        }

        /* ===== Единая база для всех кнопок ===== */
        .confirm-button,
        .confirm-button-danger,
        .btn-logout,
        .btn-search,
        .btn-theme-toggle,
        .burger,
        .flash-close,
        .btn-up,
        .pagination a,
        .pagination span,
        .user-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: var(--btn-h);
            min-height: var(--btn-h);
            padding: var(--btn-pad);
            border: 0;
            background: var(--surface-soft);
            color: var(--text-main);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.2;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease,
                transform 0.2s ease, opacity 0.2s ease, text-shadow 0.2s ease;
        }

        .confirm-button:hover,
        .confirm-button-danger:hover,
        .btn-logout:hover,
        .btn-search:hover,
        .btn-theme-toggle:hover,
        .burger:hover,
        .flash-close:hover,
        .btn-up:hover,
        .pagination a:hover,
        .user-trigger:hover,
        .user-menu.is-open .user-trigger {
            background: var(--surface-hover);
        }

        /* ===== Верхнее меню ===== */
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
            background-color: var(--nav-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: var(--shadow-nav);
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 57px;
        }

        .brand-title {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            font-size: clamp(20px, 5vw, 32px);
            font-weight: bold;
            letter-spacing: 0.02em;
            color: var(--text-main);
            text-decoration: none;
            line-height: 1;
            text-shadow: var(--glow);
            transition: text-shadow 0.25s ease, color 0.25s ease;
        }

        .brand-title:hover {
            text-shadow: var(--glow-hover);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 24px;
            line-height: 1;
            text-shadow: var(--glow);
            transition: text-shadow 0.25s ease, color 0.25s ease;
        }

        .btn-back:hover {
            text-shadow: var(--glow-hover);
        }

        .menu-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 8px;
            margin-left: -6px;
        }

        .menu-links a,
        .btn-profile {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            padding: 0 14px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            text-shadow: var(--glow);
            transition: background-color 0.25s ease, color 0.25s ease, text-shadow 0.25s ease, transform 0.25s ease;
        }

        .menu-links a:hover,
        .btn-profile:hover {
            background: var(--surface-soft);
            text-shadow: var(--glow-hover);
        }

        /* Главное действие меню: единственная залитая кнопка */
        .menu-links a.menu-cta {
            justify-content: center;
            padding: var(--btn-pad);
            background: var(--accent);
            color: var(--accent-contrast);
            font-size: 15px;
            font-weight: 700;
            text-shadow: none;
        }

        .menu-links a.menu-cta:hover {
            background: var(--accent-hover);
            color: var(--accent-contrast);
            text-shadow: none;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 0;
            color: var(--text-main);
        }

        /* ===== Flash-сообщения ===== */
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
            padding: 16px 56px 20px 18px;
            background: var(--surface-3);
            box-shadow: var(--shadow-raise);
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
            background: rgba(var(--accent-rgb), 0.5);
            transform-origin: left center;
            animation: flash-timer 4.8s linear forwards;
        }

        .flash-message.is-success::after {
            background: var(--success);
        }

        .flash-message.is-error::after {
            background: var(--danger);
        }

        .flash-label {
            display: inline-block;
            margin-bottom: 6px;
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
            text-shadow: var(--glow);
        }

        .flash-message.is-success .flash-label {
            color: var(--success);
            text-shadow: 0 0 12px rgba(var(--success-rgb), 0.5);
        }

        .flash-message.is-error .flash-label {
            color: var(--danger);
            text-shadow: 0 0 12px rgba(var(--danger-rgb), 0.5);
        }

        .flash-copy {
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-main);
        }

        .flash-close {
            position: absolute;
            top: 6px;
            right: 6px;
            width: var(--btn-h);
            height: var(--btn-h);
            padding: 0;
            background: transparent;
            color: var(--text-muted);
            font-size: 22px;
            line-height: 1;
        }

        .flash-message.is-leaving {
            animation: flash-leave 0.24s ease forwards;
        }

        /* ===== Диалог подтверждения ===== */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            z-index: 1400;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--overlay-bg);
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
            padding: 28px 24px 24px;
            background: var(--surface-3);
            box-shadow: var(--shadow-soft);
            transform: translateY(8px) scale(0.98);
            transition: transform 0.2s ease;
        }

        .confirm-overlay.is-open .confirm-dialog {
            transform: translateY(0) scale(1);
        }

        .confirm-title {
            margin: 0 0 12px;
            font-size: 28px;
            line-height: 1.15;
            text-shadow: var(--glow);
        }

        .confirm-copy {
            margin: 0;
            font-size: 16px;
            line-height: 1.75;
            color: var(--text-muted);
        }

        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .confirm-button-danger {
            background: var(--danger);
            color: var(--danger-contrast);
        }

        .confirm-button-danger:hover {
            background: var(--danger-hover);
        }

        /* ===== Пагинация ===== */
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

        .pagination span {
            cursor: default;
        }

        .pagination .active span {
            background: var(--accent);
            color: var(--accent-contrast);
        }

        .pagination .disabled span {
            opacity: 0.5;
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

        /* ===== Выход и иконки меню ===== */
        .btn-logout {
            background: rgba(var(--danger-rgb), 0.14);
            color: var(--danger);
        }

        .btn-logout:hover {
            background: rgba(var(--danger-rgb), 0.26);
            color: var(--danger);
        }

        .btn-search,
        .btn-theme-toggle {
            width: var(--btn-h);
            padding: 0;
            background: transparent;
            font-size: 18px;
            line-height: 1;
        }

        .btn-theme-toggle {
            margin-left: 4px;
        }

        .burger {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 0;
            background: transparent;
        }

        .burger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--text-main);
            box-shadow: var(--glow-faint);
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
                gap: 4px !important;
                margin: 0;
                padding: 96px 16px 24px;
                background: var(--surface-2);
                box-shadow: -16px 0 48px rgba(0, 0, 0, 0.45);
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
                display: flex;
                justify-content: flex-start;
                width: 100%;
                min-height: 48px;
                padding: 0 14px;
                margin-left: 0;
                font-size: 16px;
            }

            .menu-links a.menu-cta {
                justify-content: center;
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

        /* ===== User dropdown: горизонтальная панель под меню ===== */
        .menu-links a.is-active {
            text-decoration: underline;
            text-decoration-color: var(--accent);
            text-decoration-thickness: 2px;
            text-underline-offset: 8px;
            text-shadow: var(--glow-hover);
        }

        .user-bar-label {
            display: none;
        }

        @media (min-width: 901px) {
            .menu-links {
                align-items: center;
            }

            .menu-tools {
                display: flex;
                align-items: center;
            }

            .user-trigger {
                gap: 10px;
                padding: 4px 16px 4px 4px;
                font-size: 15px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                object-fit: cover;
                flex-shrink: 0;
            }

            .user-avatar--letter {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: var(--accent);
                color: var(--accent-contrast);
                font-size: 15px;
                font-weight: 700;
            }

            .user-name {
                max-width: 140px;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .user-chevron {
                transition: transform 0.25s ease;
            }

            .user-menu.is-open .user-chevron {
                transform: rotate(180deg);
            }

            /* li.user-menu НЕ position:relative, поэтому панель
               привязывается к .main-menu (fixed) и растягивается на всю его ширину */
            .user-bar {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 12px;
                padding: 14px 48px;
                background: var(--nav-bar-bg);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                box-shadow: var(--shadow-nav);
                clip-path: inset(0 0 100% 0);
                visibility: hidden;
                transition: clip-path 0.28s ease, visibility 0s linear 0.28s;
            }

            .user-menu.is-open .user-bar {
                clip-path: inset(0 -60px -60px -60px);
                visibility: visible;
                transition: clip-path 0.28s ease, visibility 0s;
            }

            /* выход отделён от остальных действий отступом и цветом */
            .user-bar form {
                margin-left: 28px;
            }
        }

        @media (max-width: 900px) {
            .user-trigger {
                display: none;
            }

            .user-menu {
                margin-top: 16px;
                padding: 8px;
                background: var(--surface-soft);
            }

            .user-bar {
                display: block;
                width: 100%;
            }

            .user-bar-label {
                display: block;
                padding: 8px 14px;
                font-size: 15px;
                font-weight: 600;
                color: var(--text-muted);
            }

            .user-bar .btn-logout {
                margin-top: 12px;
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






        <ul class="menu-links" data-lenis-prevent>
            <li class="menu-tools">
                <button class="btn-search" id="open-search" aria-label="Search">
                    <img src="{{ asset('images/search.svg') }}" class="search-icon-menu" alt="Search">
                </button>
                <button class="btn-theme-toggle" id="theme-toggle" aria-label="Toggle theme">
                    <img src="{{ asset('images/theme.svg') }}" class="theme-icon-menu" alt="Theme">
                </button>
            </li>

            {{-- Основные кнопки --}}
            <li><a href="{{ $postsAnchor }}" @class([
                'is-active' => request()->routeIs('posts.index', 'posts.show'),
            ])>Posts</a></li>
            <li><a href="{{ $aboutAnchor }}">About</a></li>
            <li><a href="{{ $footerAnchor }}">Contacts</a></li>

            @auth
                @php $authUser = auth()->user(); @endphp
                {{-- Всё для пользователя: горизонтальная панель, встроенная под меню --}}
                <li class="user-menu" id="user-menu">
                    <button type="button" class="user-trigger" id="user-trigger" aria-haspopup="true" aria-expanded="false"
                        aria-controls="user-bar">
                        @if ($authUser->avatar)
                            <img class="user-avatar" src="{{ $authUser->avatar }}" alt="">
                        @else
                            <span
                                class="user-avatar user-avatar--letter">{{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}</span>
                        @endif
                        <span class="user-name">{{ $authUser->name }}</span>
                        <svg class="user-chevron" width="12" height="12" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="user-bar" id="user-bar">
                        <span class="user-bar-label">{{ $authUser->name }}</span>
                        <a href="{{ route('posts.create') }}" class="menu-cta">Create post</a>
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        @if ($authUser->isAdmin())
                            <a href="{{ route('admin.panel') }}">Admin panel</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" data-confirm data-confirm-title="Log out?"
                            data-confirm-message="Are you sure you want to log out?" data-confirm-button="Log out">
                            @csrf
                            <button type="submit" class="btn-logout">Log out</button>
                        </form>
                    </div>
                </li>
            @endauth

            @guest
                <li><a href="{{ route('login') }}">Log in</a></li>
                <li><a href="{{ route('register') }}" class="menu-cta">Register</a></li>
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
                    if (window.lenis) {
                        window.lenis.scrollTo(target, {
                            offset: -108
                        });
                    } else {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }

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
            background: var(--overlay-bg);
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
            background: var(--surface-2);
            box-shadow: var(--shadow-soft);
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
            min-height: 60px;
            padding: 8px 20px;
            background: var(--surface-3);
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
        }

        /* фокус поля поиска: подсветка фона и свечение, без рамки */
        .search-input-wrap:focus-within {
            background: var(--surface-hover);
            box-shadow: inset 0 0 28px rgba(var(--accent-rgb), 0.22);
        }

        .search-icon-modal {
            font-size: 18px;
            opacity: 0.8;
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            filter: var(--icon-filter);
        }

        .search-input {
            flex: 1;
            min-height: var(--btn-h);
            background: transparent;
            border: none;
            outline: none;
            font-size: 17px;
            font-family: inherit;
            color: var(--text-main);
            caret-color: var(--accent);
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-hint {
            font-size: 14px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .search-results {
            max-height: 360px;
            overflow-y: auto;
            padding: 0 8px;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 60px;
            padding: 8px 12px;
            margin-bottom: 2px;
            text-decoration: none;
            color: var(--text-main);
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
        }

        .search-result-item:hover,
        .search-result-item.is-active {
            background: var(--surface-hover);
            box-shadow: inset 0 0 18px rgba(var(--accent-rgb), 0.15);
        }

        .search-result-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            flex-shrink: 0;
            background: var(--surface-soft);
        }

        .search-result-info {
            display: grid;
            gap: 4px;
            min-width: 0;
        }

        .search-result-title {
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-shadow: var(--glow);
            transition: text-shadow 0.2s ease;
        }

        .search-result-item:hover .search-result-title,
        .search-result-item.is-active .search-result-title {
            text-shadow: var(--glow-hover);
        }

        .search-result-title mark {
            background: transparent;
            color: var(--accent);
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .search-result-excerpt {
            font-size: 14px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-section-title {
            padding: 14px 12px 6px;
            font-size: 14px;
            font-weight: 700;
            color: var(--accent);
            text-shadow: var(--glow-faint);
        }

        .search-result-img.is-avatar {}

        .search-empty {
            padding: 28px 20px;
            text-align: center;
            font-size: 15px;
            color: var(--text-muted);
        }

        .search-footer {
            margin-top: 8px;
            padding: 12px 20px;
            background: var(--surface-3);
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .search-footer kbd {
            display: inline-block;
            padding: 2px 8px;
            background: var(--surface-hover);
            font-family: inherit;
            font-size: 14px;
        }

        .search-icon-menu {
            width: 24.7px;
            height: 24.7px;
            opacity: 0.85;
            filter: var(--icon-filter);
        }

        .theme-icon-menu {
            width: 24.7px;
            height: 24.7px;
            opacity: 0.85;
            display: block;
            filter: var(--icon-filter);
        }

        .search-spinner {
            display: none;
            justify-content: center;
            padding: 28px 20px;
        }

        .search-spinner.is-loading {
            display: flex;
        }

        /* квадратное кольцо без border: конический градиент с маской */
        .spinner {
            width: 28px;
            height: 28px;
            padding: 3px;
            background: conic-gradient(var(--accent) 0 28%, var(--surface-hover) 0);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#000 0 0) content-box exclude, linear-gradient(#000 0 0);
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
            padding: 0;
            background: var(--surface-3);
            color: var(--text-main);
            font-size: 26px;
            text-shadow: var(--glow);
            box-shadow: var(--shadow-raise);
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            z-index: 1100;
        }

        .btn-up.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .btn-up:hover {
            text-shadow: var(--glow-hover);
        }

        /* Без мерцаний; при reduced-motion убираем анимации и плавные переходы */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }

            .spinner {
                animation-duration: 1.6s !important;
                animation-iteration-count: infinite !important;
            }

        }
    </style>

    <div class="search-overlay" id="search-overlay" aria-hidden="true">
        <div class="search-box" role="dialog" aria-label="Search posts and people">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <img src="{{ asset('images/search.svg') }}" class="search-icon-modal" alt="Search Modal">
                </span>
                <input type="text" class="search-input" id="search-input"
                    placeholder="Search posts and people..." autocomplete="off" aria-label="Search">
                <span class="search-hint">ESC to close</span>
            </div>
            <div class="search-results" id="search-results" data-lenis-prevent>
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
            let requestId = 0;

            const defaultAvatar = '{{ asset('images/default-avatar.svg') }}';
            const userSearchUrl =
                '{{ \Illuminate\Support\Facades\Route::has('users.search') ? route('users.search') : url('/users/search') }}';

            const escapeHtml = (value) => String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');

            const highlightSafe = (text, query) => {
                const raw = String(text ?? '');
                if (!query) return escapeHtml(raw);
                const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return raw
                    .split(new RegExp(`(${escapedQuery})`, 'gi'))
                    .map((part, i) => i % 2 ? `<mark>${escapeHtml(part)}</mark>` : escapeHtml(part))
                    .join('');
            };

            const normalizeAvatar = (src) => {
                if (!src) return defaultAvatar;
                const nested = String(src).match(/\/storage\/(https?:\/\/.+)$/);
                return nested ? nested[1] : src;
            };

            const fetchJson = async (url) => {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error('Request failed: ' + res.status);
                return res.json();
            };


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


            const renderResults = ({
                posts,
                users
            }, query) => {
                activeIndex = -1;

                if (!posts.length && !users.length) {
                    results.innerHTML =
                        `<p class="search-empty">Nothing found for "<strong>${escapeHtml(query)}</strong>"</p>`;
                    return;
                }

                const postsHtml = posts.length ? `
                <div class="search-section-title">Posts</div>
                ${posts.map((post) => `
                                    <a href="${post.url}" class="search-result-item">
                                        <img src="${post.image}" alt="" class="search-result-img">
                                        <div class="search-result-info">
                                            <div class="search-result-title">${highlight(post.title, query)}</div>
                                            <div class="search-result-excerpt">${post.excerpt}</div>
                                        </div>
                                    </a>
                                    `).join('')}` : '';

                const usersHtml = users.length ? `
                <div class="search-section-title">People</div>
                ${users.map((user) => `
                                    <a href="${escapeHtml(user.url)}" class="search-result-item">
                                        <img src="${escapeHtml(normalizeAvatar(user.avatar))}" alt=""
                                            class="search-result-img is-avatar"
                                            onerror="this.onerror=null;this.src='${defaultAvatar}'">
                                        <div class="search-result-info">
                                            <div class="search-result-title">${highlightSafe(user.name, query)}</div>
                                            <div class="search-result-excerpt">View profile</div>
                                        </div>
                                    </a>
                                    `).join('')}` : '';

                results.innerHTML = postsHtml + usersHtml;
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

                    const currentRequest = ++requestId;

                    try {
                        const [postsResult, usersResult] = await Promise.allSettled([
                            fetchJson(`/posts/search?q=${encodeURIComponent(query)}`),
                            fetchJson(`${userSearchUrl}?q=${encodeURIComponent(query)}`),
                        ]);

                        if (currentRequest !== requestId) return;

                        if (postsResult.status === 'rejected' && usersResult.status ===
                            'rejected') {
                            throw new Error('Search failed');
                        }

                        const toList = (result) =>
                            result.status === 'fulfilled' && Array.isArray(result.value) ? result
                            .value : [];

                        renderResults({
                            posts: toList(postsResult),
                            users: toList(usersResult)
                        }, query);
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
                    if (window.lenis) {
                        window.lenis.scrollTo(0);
                    } else {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
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



    <script>
        (() => {
            const menu = document.getElementById('user-menu');
            const trigger = document.getElementById('user-trigger');
            if (!menu || !trigger) return;

            const setOpen = (open) => {
                menu.classList.toggle('is-open', open);
                trigger.setAttribute('aria-expanded', String(open));
            };

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                setOpen(!menu.classList.contains('is-open'));
            });

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target)) setOpen(false);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') setOpen(false);
            });

            document.getElementById('open-search')?.addEventListener('click', () => setOpen(false));
        })();
    </script>


    <script>
        (() => {
            if (typeof Lenis === 'undefined') {
                console.warn('Lenis не загрузился (проверьте CDN-скрипт в <head>)');
                return;
            }

            const lenis = new Lenis({
                duration: 2.0, // больше = медленнее и «кинематографичнее»
                wheelMultiplier: 0.8, // меньше = короче шаг на одно колесо мыши
                smoothWheel: true,
            });
            window.lenis = lenis;

            const raf = (time) => {
                lenis.raf(time);
                requestAnimationFrame(raf);
            };
            requestAnimationFrame(raf);

            // Останавливаем плавный скролл страницы, пока открыты поиск, подтверждение или мобильное меню
            const syncLock = () => {
                const locked = document.querySelector(
                    '.search-overlay.is-open, .confirm-overlay.is-open, .menu-links.is-open'
                );
                locked ? lenis.stop() : lenis.start();
            };
            new MutationObserver(syncLock).observe(document.body, {
                subtree: true,
                attributes: true,
                attributeFilter: ['class'],
            });
        })();
    </script>

</body>

</html>
