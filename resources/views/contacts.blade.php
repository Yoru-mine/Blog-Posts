@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
    <style>
        .contacts-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 32%);
        }

        .contacts-shell {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            gap: 30px;
        }

        .contacts-hero,
        .contacts-grid {
            background: rgba(28, 28, 28, 0.76);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .contacts-hero {
            padding: 38px;
        }

        .contacts-kicker {
            display: inline-block;
            margin-bottom: 16px;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.56);
        }

        .contacts-title {
            margin: 0 0 16px;
            font-size: 54px;
            line-height: 1.02;
        }

        .contacts-copy {
            max-width: 720px;
            margin: 0;
            font-size: 17px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.76);
        }

        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0;
        }

        .contacts-card {
            position: relative;
            padding: 30px;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            isolation: isolate;
        }

        .contacts-card:last-child {
            border-right: none;
        }

        .contacts-card::before {
            content: "";
            position: absolute;
            top: 18px;
            right: 10px;
            width: 180px;
            height: 180px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            filter: blur(3px);
            opacity: 0.17;
            z-index: -1;
            pointer-events: none;
            transform: rotate(-10deg);
        }

        .contacts-card.email-card::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect x='8' y='16' width='48' height='32' rx='4' fill='none' stroke='white' stroke-width='3'/%3E%3Cpath d='M10 20l22 18 22-18' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        }

        .contacts-card.anon-card::before {
            background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cpath d='M14 18h36a4 4 0 0 1 4 4v20a4 4 0 0 1-4 4H28l-10 8v-8h-4a4 4 0 0 1-4-4V22a4 4 0 0 1 4-4Z' fill='none' stroke='white' stroke-width='3' stroke-linejoin='round'/%3E%3Cpath d='M22 28h20M22 36h12' fill='none' stroke='white' stroke-width='3' stroke-linecap='round'/%3E%3C/svg%3E\");
        }

        .contacts-card.instagram-card::before {
            background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect x='14' y='14' width='36' height='36' rx='10' fill='none' stroke='white' stroke-width='3'/%3E%3Ccircle cx='32' cy='32' r='9' fill='none' stroke='white' stroke-width='3'/%3E%3Ccircle cx='43.5' cy='20.5' r='2.5' fill='white'/%3E%3C/svg%3E\");
        }

        .contacts-label {
            display: inline-block;
            margin-bottom: 14px;
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
        }

        .contacts-value {
            margin: 0 0 12px;
            font-size: 28px;
            line-height: 1.2;
        }

        .contacts-note {
            margin: 0;
            font-size: 14px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.7);
        }

        .contacts-value a {
            color: #f2ede2;
            text-decoration: none;
            word-break: break-word;
        }

        .contacts-value a:hover {
            text-decoration: underline;
        }

        .contacts-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            color: rgba(255, 255, 255, 0.74);
            text-decoration: none;
            font-size: 14px;
            transition: transform 0.2s ease, opacity 0.2s ease, color 0.2s ease;
        }

        .contacts-link:hover {
            transform: translateY(-1px);
            opacity: 0.96;
            color: #fff;
        }

        .contacts-link-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.06);
            color: #f2ede2;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        @media (max-width: 900px) {
            .contacts-page {
                padding: 112px 14px 36px;
            }

            .contacts-hero {
                padding: 24px 18px;
            }

            .contacts-title {
                font-size: 38px;
            }

            .contacts-grid {
                grid-template-columns: 1fr;
            }

            .contacts-card {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .contacts-card:last-child {
                border-bottom: none;
            }

            .contacts-card::before {
                width: 130px;
                height: 130px;
            }
        }
    </style>

    <div class="contacts-page">
        <div class="contacts-shell">
            <section class="contacts-hero">
                <span class="contacts-kicker">Stay connected</span>
                <h1 class="contacts-title">Contacts</h1>
                <p class="contacts-copy">
                    This page is a simple contact space inside the same visual atmosphere as the rest of the project. It can later grow into a more personal portfolio section with real links and ways to reach you.
                </p>
            </section>

            <section class="contacts-grid">
                <div class="contacts-card email-card">
                    <span class="contacts-label">Email</span>
                    <p class="contacts-value"><a href="mailto:myavk22@gmail.com">myavk22@gmail.com</a></p>
                    <p class="contacts-note">The easiest direct way to reach me for project questions, feedback, or collaboration.</p>
                    <a href="mailto:myavk22@gmail.com" class="contacts-link">
                        <span class="contacts-link-mark">GM</span>
                        Write by email
                    </a>
                </div>

                <div class="contacts-card anon-card">
                    <span class="contacts-label">Anonymous messages</span>
                    <p class="contacts-value">
                        <a href="https://t.me/anonaskbot?start=us_4jwh3y" target="_blank" rel="noopener noreferrer">
                            t.me/anonaskbot
                        </a>
                    </p>
                    <p class="contacts-note">A quick way to send an anonymous message when you want to stay private but still say something.</p>
                    <a href="https://t.me/anonaskbot?start=us_4jwh3y" target="_blank" rel="noopener noreferrer" class="contacts-link">
                        <span class="contacts-link-mark">TG</span>
                        Open anonymous chat
                    </a>
                </div>

                <div class="contacts-card instagram-card">
                    <span class="contacts-label">Instagram</span>
                    <p class="contacts-value">
                        <a href="https://instagram.com/myavk88" target="_blank" rel="noopener noreferrer">
                            @myavk88
                        </a>
                    </p>
                    <p class="contacts-note">A more personal space if you want to connect outside the project and keep in touch there too.</p>
                    <a href="https://instagram.com/myavk88" target="_blank" rel="noopener noreferrer" class="contacts-link">
                        <span class="contacts-link-mark">IG</span>
                        Open Instagram
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
