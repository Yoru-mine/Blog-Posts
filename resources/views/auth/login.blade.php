@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <style>
        body {
            background-image: url('{{ asset('images/gradient-back.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-page {
            min-height: 100vh;
            padding: 118px 24px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: var(--surface-3);
            width: 520px;
            min-height: 560px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.80);
            padding: 18px 0 30px;
        }

        .login-page h1 {
            font-size: 32px;
            text-align: center;
            margin: 0 0 30px;
            font-weight: 700;
            letter-spacing: 0.01em;
            text-shadow: var(--glow);
        }

        .login-field {
            width: 424px;
            margin: 0 auto 14px;
        }

        .login-field label {
            display: block;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        /* поля без рамок: фон + свечение при наведении и фокусе */
        .login-field input {
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

        .login-field input:hover {
            background: rgba(var(--accent-rgb), 0.12);
        }

        .login-field input:focus {
            outline: none;
            background: rgba(var(--accent-rgb), 0.16);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 26px rgba(var(--accent-rgb), 0.24);
        }

        .login-field input[aria-invalid="true"] {
            background: rgba(var(--danger-rgb), 0.12);
            box-shadow: 0 0 0 3px rgba(var(--danger-rgb), 0.3);
        }

        .login-field input:-webkit-autofill,
        .login-field input:-webkit-autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px var(--surface-2) inset !important;
            -webkit-text-fill-color: var(--text-main) !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .login-field input:-webkit-autofill:focus,
        .login-field input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px var(--surface-2) inset, 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 26px rgba(var(--accent-rgb), 0.24) !important;
            -webkit-text-fill-color: var(--text-main) !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .login-error {
            margin-top: 8px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--danger);
        }

        .login-status {
            width: 424px;
            margin: 0 auto 18px;
            padding: 12px 14px;
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-main);
            background: var(--surface-soft);
        }

        .login-forgot {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .login-forgot a {
            display: inline-flex;
            align-items: center;
            min-height: var(--btn-h);
            padding: 0 14px;
            font-size: 15px;
            color: var(--text-muted);
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease, text-shadow 0.2s ease;
        }

        .login-forgot a:hover,
        .login-forgot a:focus-visible {
            background: var(--surface-soft);
            color: var(--text-main);
            text-shadow: var(--glow);
        }

        .login-remember {
            width: 424px;
            margin: 14px auto 0;
        }

        .login-remember label {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: var(--btn-h);
            font-size: 15px;
            color: var(--text-main);
            cursor: pointer;
        }

        /* чекбокс без рамки: плотный фон, галочка вырезана clip-path */
        #remember_me {
            appearance: none;
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            margin: 0;
            border: 0;
            background: var(--surface-hover);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        #remember_me:hover {
            background: rgba(var(--accent-rgb), 0.18);
        }

        #remember_me:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.28), 0 0 20px rgba(var(--accent-rgb), 0.22);
        }

        #remember_me:checked {
            background: var(--accent);
        }

        #remember_me:checked::after {
            content: "";
            position: absolute;
            inset: 5px;
            background: var(--accent-contrast);
            clip-path: polygon(14% 52%, 0 66%, 38% 100%, 100% 22%, 86% 8%, 38% 72%);
        }

        /* главное действие экрана: единственная залитая кнопка */
        .login-button {
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

        .login-button:hover,
        .login-button:focus-visible {
            background: var(--accent-hover);
        }

        .login-button[aria-busy="true"] {
            opacity: 0.65;
            pointer-events: none;
        }

        .login-register {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }

        .login-register a {
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

        .login-register a:hover,
        .login-register a:focus-visible {
            background: var(--surface-soft);
            text-shadow: var(--glow-hover);
        }

        @media (max-width: 640px) {
            .login-page {
                padding: 112px 14px 24px;
            }

            .login-box {
                width: 100%;
                min-height: auto;
                padding: 18px 0 28px;
            }

            .login-field,
            .login-status,
            .login-remember {
                width: calc(100% - 32px);
            }

            .login-field input {
                width: 100%;
            }

            .login-button {
                width: calc(100% - 120px);
                min-width: 180px;
            }
        }
    </style>
    <div class="login-page">
        <div class="login-box">
            <h1>Log in</h1>

            @if (session('status'))
                <div class="login-status" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login-field">
                    <label for="login">Email or username</label>
                    <input id="login" type="text" name="login" value="{{ old('login', old('email')) }}" required
                        autofocus autocomplete="username" autocapitalize="none"
                        @if ($errors->has('login') || $errors->has('email')) aria-invalid="true" aria-describedby="login-error" @endif>
                    @if ($errors->has('login') || $errors->has('email'))
                        <div class="login-error" id="login-error" role="alert">
                            {{ $errors->first('login') ?: $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    @error('password')
                        <div class="login-error" id="password-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                @if (Route::has('password.request'))
                    <div class="login-forgot">
                        <a href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </div>
                @endif


                <div class="login-remember">
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>
                <div class="login-register">
                    <a href="{{ route('register') }}">Haven't registered yet?</a>
                </div>

                <button type="submit" class="login-button">
                    Log in
                </button>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const form = document.querySelector('.login-box form');
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
