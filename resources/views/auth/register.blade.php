@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <style>
        body {
            background-image: url('{{ asset('images/gradient-back.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .register-page {
            min-height: 100vh;
            padding: 118px 24px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background-color: var(--surface-3);
            width: 560px;
            min-height: 760px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.80);
            padding: 18px 0 30px;
        }

        .register-page h1 {
            font-size: 32px;
            text-align: center;
            margin: 0 0 30px;
            font-weight: 700;
            letter-spacing: 0.01em;
            text-shadow: var(--glow);
        }

        .register-field {
            width: 424px;
            margin: 0 auto 14px;
        }

        .register-field label {
            display: block;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        /* поля без рамок: фон + свечение при наведении и фокусе */
        .register-field input {
            display: block;
            width: 424px;
            height: 56px;
            box-sizing: border-box;
            background: var(--surface-hover);
            border: 0;
            outline: none;
            color: var(--text-main);
            caret-color: var(--accent);
            padding: 0 16px;
            font-size: 16px;
            font-family: inherit;
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
        }

        .register-field input:hover {
            background: rgba(var(--accent-rgb), 0.12);
        }

        .register-field input:focus {
            outline: none;
            background: rgba(var(--accent-rgb), 0.16);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 26px rgba(var(--accent-rgb), 0.24);
        }

        .register-field input[aria-invalid="true"] {
            background: rgba(var(--danger-rgb), 0.12);
            box-shadow: 0 0 0 3px rgba(var(--danger-rgb), 0.3);
        }

        .register-field input:-webkit-autofill,
        .register-field input:-webkit-autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px var(--surface-2) inset !important;
            -webkit-text-fill-color: var(--text-main) !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .register-field input:-webkit-autofill:focus,
        .register-field input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px var(--surface-2) inset, 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 26px rgba(var(--accent-rgb), 0.24) !important;
            -webkit-text-fill-color: var(--text-main) !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .register-error {
            margin-top: 8px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--danger);
        }

        /* главное действие экрана: единственная залитая кнопка */
        .register-button {
            display: block;
            margin: 32px auto 0;
            width: 260px;
            min-height: 52px;
            font-size: 18px;
            font-weight: 700;
            border: none;
            background: var(--accent);
            color: var(--accent-contrast);
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.2s ease, opacity 0.2s ease;
        }

        .register-button:hover,
        .register-button:focus-visible {
            background: var(--accent-hover);
        }

        .register-button[aria-busy="true"] {
            opacity: 0.65;
            pointer-events: none;
        }

        .register-login {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }

        .register-login a {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            padding: 0 14px;
            font-size: 16px;
            color: var(--text-main);
            text-decoration: none;
            text-shadow: var(--glow-faint);
            transition: background-color 0.2s ease, text-shadow 0.2s ease;
        }

        .register-login a:hover,
        .register-login a:focus-visible {
            background: var(--surface-soft);
            text-shadow: var(--glow-hover);
        }

        @media (max-width: 640px) {
            .register-page {
                padding: 112px 14px 24px;
            }

            .register-box {
                width: 100%;
                min-height: auto;
                padding: 18px 0 28px;
            }

            .register-field {
                width: calc(100% - 32px);
            }

            .register-field input {
                width: 100%;
            }

            .register-button {
                width: calc(100% - 120px);
                min-width: 180px;
            }
        }
    </style>

    <div class="register-page">
        <div class="register-box">
            <h1>Register</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="register-field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name')
                        <div class="register-error" id="name-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                        minlength="3" maxlength="30" pattern="[A-Za-z0-9_]{3,30}" autocomplete="nickname"
                        autocapitalize="none"
                        @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
                    @error('username')
                        <div class="register-error" id="username-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username"
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <div class="register-error" id="email-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <div class="register-error" id="password-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password"
                        @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror>
                    @error('password_confirmation')
                        <div class="register-error" id="password_confirmation-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-login">
                    <a href="{{ route('login') }}">Already registered?</a>
                </div>

                <button type="submit" class="register-button">
                    Register
                </button>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const form = document.querySelector('.register-box form');
            const firstInvalid = document.querySelector('[aria-invalid="true"]');
            if (firstInvalid) firstInvalid.focus();

            // защита от двойной отправки
            form?.addEventListener('submit', () => {
                form.querySelector('button[type="submit"]')?.setAttribute('aria-busy', 'true');
            });
            window.addEventListener('pageshow', () => {
                form?.querySelector('button[type="submit"]')?.removeAttribute('aria-busy');
            });
        })();
    </script>
@endsection
