@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @php
        $avatarUrl = $user->avatar ? $user->avatar : asset('images/default-avatar.svg');
    @endphp

    <style>
        .profile-page {
            position: relative;
            min-height: 100vh;
            padding: 132px 24px 72px;
            overflow: hidden;
        }

        .profile-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(30px);
            transform: scale(1.1);
            opacity: 0.48;
        }

        .profile-backdrop::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(17, 17, 17, 0.32), rgba(17, 17, 17, 0.72)),
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%);
        }

        .profile-shell {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            gap: 28px;
        }

        .profile-hero {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 24px;
            padding: 36px 38px;
            background: rgba(28, 28, 28, 0.78);
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(12px);
        }

        .profile-hero-main {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 28px;
            align-items: start;
        }

        .profile-hero-copy {
            min-width: 0;
        }

        .profile-kicker {
            display: inline-block;
            margin-bottom: 18px;
            font-size: 14px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .profile-title {
            margin: 0 0 16px;
            font-size: 52px;
            line-height: 1.03;
        }

        .profile-copy {
            max-width: 640px;
            margin: 0;
            font-size: 17px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.78);
        }

        .profile-side-panel {
            display: grid;
            gap: 16px;
        }

        .profile-avatar-preview {
            width: 280px;
            height: 280px;
            justify-self: center;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.22);
            flex-shrink: 0;
        }

        .profile-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .profile-action,
        .profile-action-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: var(--btn-h);
            padding: 0 22px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* навигационные кнопки героя вторичные: главное действие экрана — «Save changes» */
        .profile-action,
        .profile-action-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
        }

        .profile-action:hover,
        .profile-action-secondary:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .profile-stat {
            min-height: 132px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.04);
            overflow: hidden;
        }

        .profile-stat-label {
            margin-bottom: 16px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.56);
        }

        .profile-stat-value {
            margin: 0 0 10px;
            font-size: 38px;
            line-height: 1;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .profile-stat-note {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.72);
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .profile-card {
            padding: 30px;
            background: rgba(30, 30, 30, 0.72);
            box-shadow: var(--shadow-soft);
        }

        .profile-card-danger {
            grid-column: 1 / -1;
            background: linear-gradient(180deg, rgba(72, 28, 28, 0.28), rgba(30, 30, 30, 0.82));
        }

        .profile-card-title {
            margin: 0 0 12px;
            font-size: 28px;
        }

        .profile-card-copy {
            margin: 0 0 24px;
            font-size: 15px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.72);
        }

        .profile-form {
            display: grid;
            gap: 18px;
        }

        .profile-field {
            display: grid;
            gap: 10px;
        }

        .profile-label {
            font-size: 14px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.56);
        }

        .profile-input {
            width: 100%;
            min-height: 58px;
            padding: 0 18px;
            background: rgba(0, 0, 0, 0.38);
            color: #f5f5f5;
            font-size: 16px;
            font-family: inherit;
            outline: none;
            transition: box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .profile-input:hover {
            background: rgba(0, 0, 0, 0.5);
        }

        .profile-input:focus {
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 24px rgba(var(--accent-rgb), 0.2);
        }

        .profile-input[aria-invalid="true"] {
            background: rgba(var(--danger-rgb), 0.1);
            box-shadow: 0 0 0 3px rgba(var(--danger-rgb), 0.3);
        }

        .profile-file-field {
            position: relative;
            display: grid;
            gap: 10px;
        }

        .profile-file-input {
            width: 100%;
            min-height: 58px;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.38);
            color: rgba(255, 255, 255, 0.72);
            font-family: inherit;
            font-size: 15px;
            cursor: pointer;
        }

        .profile-file-input::file-selector-button {
            margin-right: 14px;
            min-height: 36px;
            padding: 0 16px;
            border: 0;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .profile-file-input:hover::file-selector-button {
            background: rgba(255, 255, 255, 0.18);
        }

        .profile-file-input:focus-visible {
            outline: none;
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 24px rgba(var(--accent-rgb), 0.2);
        }

        .profile-file-note {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.56);
        }

        .profile-avatar-remove {
            justify-self: start;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: var(--btn-h);
            padding: 0 18px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            color: var(--danger);
            background: rgba(var(--danger-rgb), 0.14);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .profile-avatar-remove:hover,
        .profile-avatar-remove:focus-visible {
            background: rgba(var(--danger-rgb), 0.26);
        }

        .profile-button,
        .profile-button-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: var(--btn-h);
            padding: 0 22px;
            border: none;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, opacity 0.2s ease;
        }

        /* главное действие экрана */
        .profile-button {
            background: var(--accent);
            color: var(--accent-contrast);
            font-weight: 700;
        }

        .profile-button:hover {
            background: var(--accent-hover);
        }

        .profile-button-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            font-weight: 600;
        }

        .profile-button-secondary:hover {
            background: rgba(255, 255, 255, 0.14);
        }

        /* опасное действие: красный тон, отдельная карточка, диалог подтверждения */
        .profile-button-danger {
            background: rgba(var(--danger-rgb), 0.14);
            color: var(--danger);
        }

        .profile-button-danger:hover {
            background: rgba(var(--danger-rgb), 0.26);
        }

        .profile-button[aria-busy="true"] {
            opacity: 0.65;
            pointer-events: none;
        }

        .profile-status {
            font-size: 15px;
            color: var(--success);
        }

        .profile-error {
            font-size: 14px;
            line-height: 1.5;
            color: var(--danger);
        }

        .verification-note {
            margin-top: 4px;
            font-size: 14px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.72);
        }

        .verification-button {
            display: inline;
            border: none;
            background: none;
            color: var(--accent);
            font-family: inherit;
            font-size: 15px;
            text-decoration: underline;
            text-underline-offset: 4px;
            cursor: pointer;
            padding: 12px 0;
        }

        .profile-actions-row {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .profile-title,
        .profile-card-title,
        .profile-stat-value {
            text-shadow: var(--glow);
        }

        @media (max-width: 1100px) {

            .profile-hero,
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-hero-main {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .profile-title {
                font-size: 42px;
            }
        }

        @media (max-width: 720px) {
            .profile-page {
                padding: 112px 14px 40px;
            }

            .profile-hero,
            .profile-card {
                padding: 22px 18px;
            }

            .profile-title {
                font-size: 34px;
            }

            .profile-stats {
                grid-template-columns: 1fr;
            }

            .profile-hero-actions {
                flex-direction: column;
            }

            .profile-avatar-preview {
                width: 180px;
                height: 180px;
            }

            .profile-action,
            .profile-action-secondary,
            .profile-button,
            .profile-button-danger {
                width: 100%;
            }
        }
    </style>

    <div class="profile-page">
        <div class="profile-backdrop" style="background-image: url('{{ $avatarUrl }}');"></div>

        <div class="profile-shell">
            <section class="profile-hero">
                <div class="profile-hero-main">
                    <div class="profile-avatar-preview" style="background-image: url('{{ $avatarUrl }}');"></div>

                    <div class="profile-hero-copy">
                        <span class="profile-kicker">Account space</span>
                        <h1 class="profile-title">{{ $user->name }}</h1>
                        @if ($user->username)
                            <p style="margin: -6px 0 16px; opacity: .6;">{{ '@' . $user->username }}</p>
                        @endif
                        <p class="profile-copy">
                            This is your profile page. Here you can update your basic information, change your password, and
                            manage your account without leaving the same atmosphere as the rest of the project.
                        </p>

                        <div class="profile-hero-actions">
                            <a href="{{ route('dashboard') }}" class="profile-action">Open dashboard</a>
                            <a href="{{ route('posts.create') }}" class="profile-action-secondary">Create post</a>
                        </div>
                    </div>
                </div>

                <div class="profile-side-panel">
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <div class="profile-stat-label">Email</div>
                            <p class="profile-stat-value" style="font-size: 22px; line-height: 1.35;">{{ $user->email }}
                            </p>
                            <div class="profile-stat-note">The email currently attached to your account.</div>
                        </div>

                        <div class="profile-stat">
                            <div class="profile-stat-label">Posts created</div>
                            <p class="profile-stat-value">{{ $postsCount }}</p>
                            <div class="profile-stat-note">All posts currently linked to your account.</div>
                        </div>

                        <div class="profile-stat">
                            <div class="profile-stat-label">Latest post</div>
                            <p class="profile-stat-value">{{ $latestPost ? $latestPost->created_at->format('d M') : '—' }}
                            </p>
                            <div class="profile-stat-note">{{ $latestPost ? $latestPost->title : 'No posts yet.' }}</div>
                        </div>

                        <div class="profile-stat">
                            <div class="profile-stat-label">Account status</div>
                            <p class="profile-stat-value" style="font-size: 22px; line-height: 1.35;">
                                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                            </p>
                            <div class="profile-stat-note">A simple overview of your account verification state.</div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="profile-grid">
                <section class="profile-card">
                    <h2 class="profile-card-title">Profile information</h2>
                    <p class="profile-card-copy">Update your name and email address. These details will be used across your
                        account and future user-facing sections.</p>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    @if ($user->avatar && \Illuminate\Support\Facades\Route::has('profile.avatar.destroyAvatar'))
                        <form id="remove-avatar-form" method="post" action="{{ route('profile.avatar.destroyAvatar') }}"
                            data-confirm data-confirm-title="Remove avatar?"
                            data-confirm-message="Your avatar will be replaced with the default image."
                            data-confirm-button="Remove avatar">
                            @csrf
                            @method('delete')
                        </form>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="profile-form"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="profile-file-field">
                            <label for="avatar" class="profile-label">Avatar</label>
                            <input id="avatar" name="avatar" type="file" class="profile-file-input" accept="image/*">
                            <div class="profile-file-note">
                                Choose an image here to update both your profile avatar and the blurred background of this
                                page.
                            </div>

                            @if ($user->avatar && \Illuminate\Support\Facades\Route::has('profile.avatar.destroyAvatar'))
                                <button type="submit" form="remove-avatar-form" class="profile-avatar-remove">Remove
                                    avatar</button>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="name" class="profile-label">Name</label>
                            <input id="name" name="name" type="text" class="profile-input"
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                                @if ($errors->get('name')) aria-invalid="true" aria-describedby="name-error" @endif>
                            @if ($errors->get('name'))
                                <div class="profile-error" id="name-error" role="alert">{{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="username" class="profile-label">Username</label>
                            <input id="username" name="username" type="text" class="profile-input"
                                value="{{ old('username', $user->username) }}" required minlength="3" maxlength="30"
                                pattern="[A-Za-z0-9_]{3,30}" autocomplete="nickname" autocapitalize="none"
                                @if ($errors->get('username')) aria-invalid="true" aria-describedby="username-error" @endif>
                            @if ($errors->get('username'))
                                <div class="profile-error" id="username-error" role="alert">
                                    {{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="email" class="profile-label">Email</label>
                            <input id="email" name="email" type="email" class="profile-input"
                                value="{{ old('email', $user->email) }}" required autocomplete="username"
                                @if ($errors->get('email')) aria-invalid="true" aria-describedby="email-error" @endif>
                            @if ($errors->get('email'))
                                <div class="profile-error" id="email-error" role="alert">{{ $errors->first('email') }}
                                </div>
                            @endif

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <p class="verification-note">
                                    Your email address is unverified.
                                    <button form="send-verification" class="verification-button">Send verification email
                                        again</button>
                                </p>
                            @endif

                            @if (session('status') === 'verification-link-sent')
                                <div class="profile-status" role="status">A new verification link has been sent to your
                                    email address.
                                </div>
                            @endif
                        </div>

                        <div class="profile-actions-row">
                            <button type="submit" class="profile-button">Save changes</button>
                            @if (session('status') === 'profile-updated')
                                <div class="profile-status" role="status">Profile updated.</div>
                            @endif
                        </div>
                    </form>
                </section>

                <section class="profile-card">
                    <h2 class="profile-card-title">Update password</h2>
                    <p class="profile-card-copy">Set a stronger password for your account. Use something long and memorable
                        for you, but hard to guess for anyone else.</p>

                    <form method="post" action="{{ route('password.update') }}" class="profile-form">
                        @csrf
                        @method('put')

                        <div class="profile-field">
                            <label for="current_password" class="profile-label">Current password</label>
                            <input id="current_password" name="current_password" type="password" class="profile-input"
                                autocomplete="current-password"
                                @if ($errors->updatePassword->get('current_password')) aria-invalid="true" aria-describedby="current_password-error" @endif>
                            @if ($errors->updatePassword->get('current_password'))
                                <div class="profile-error" id="current_password-error" role="alert">
                                    {{ $errors->updatePassword->first('current_password') }}</div>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="password" class="profile-label">New password</label>
                            <input id="password" name="password" type="password" class="profile-input"
                                autocomplete="new-password"
                                @if ($errors->updatePassword->get('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                            @if ($errors->updatePassword->get('password'))
                                <div class="profile-error" id="password-error" role="alert">
                                    {{ $errors->updatePassword->first('password') }}</div>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="password_confirmation" class="profile-label">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="profile-input" autocomplete="new-password"
                                @if ($errors->updatePassword->get('password_confirmation')) aria-invalid="true" aria-describedby="password_confirmation-error" @endif>
                            @if ($errors->updatePassword->get('password_confirmation'))
                                <div class="profile-error" id="password_confirmation-error" role="alert">
                                    {{ $errors->updatePassword->first('password_confirmation') }}
                                </div>
                            @endif
                        </div>

                        <div class="profile-actions-row">
                            <button type="submit" class="profile-button profile-button-secondary">Update
                                password</button>
                            @if (session('status') === 'password-updated')
                                <div class="profile-status" role="status">Password updated.</div>
                            @endif
                        </div>
                    </form>
                </section>

                <section class="profile-card profile-card-danger">
                    <h2 class="profile-card-title">Delete account</h2>
                    <p class="profile-card-copy">This action permanently removes your account. If you decide to continue,
                        enter your current password and confirm the deletion.</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="profile-form" data-confirm
                        data-confirm-title="Delete account?"
                        data-confirm-message="Your account and its access will be removed permanently. Make sure you really want to do this."
                        data-confirm-button="Delete account">
                        @csrf
                        @method('delete')

                        <div class="profile-field">
                            <label for="delete_password" class="profile-label">Current password</label>
                            <input id="delete_password" name="password" type="password" class="profile-input"
                                placeholder="Enter your password to confirm"
                                @if ($errors->userDeletion->get('password')) aria-invalid="true" aria-describedby="delete_password-error" @endif>
                            @if ($errors->userDeletion->get('password'))
                                <div class="profile-error" id="delete_password-error" role="alert">
                                    {{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>

                        <div class="profile-actions-row">
                            <button type="submit" class="profile-button-danger">Delete account</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <script>
        (() => {
            // Предпросмотр выбранного аватара до сохранения
            const fileInput = document.getElementById('avatar');
            const preview = document.querySelector('.profile-avatar-preview');
            let previewUrl = null;

            fileInput?.addEventListener('change', () => {
                const file = fileInput.files && fileInput.files[0];
                if (!file || !preview || !file.type.startsWith('image/')) return;
                if (previewUrl) URL.revokeObjectURL(previewUrl);
                previewUrl = URL.createObjectURL(file);
                preview.style.backgroundImage = `url('${previewUrl}')`;
            });

            // После ошибки валидации сразу ставим фокус на первое проблемное поле
            const firstInvalid = document.querySelector('[aria-invalid="true"]');
            if (firstInvalid) {
                firstInvalid.focus({
                    preventScroll: true
                });
                firstInvalid.scrollIntoView({
                    block: 'center'
                });
            }

            // Защита от двойной отправки (формы с диалогом подтверждения не трогаем)
            document.querySelectorAll('.profile-form:not([data-confirm])').forEach((form) => {
                form.addEventListener('submit', () => {
                    const button = form.querySelector('button[type="submit"]');
                    if (button) button.setAttribute('aria-busy', 'true');
                });
            });

            window.addEventListener('pageshow', () => {
                document.querySelectorAll('[aria-busy="true"]').forEach((el) => el.removeAttribute(
                'aria-busy'));
            });
        })();
    </script>
@endsection
