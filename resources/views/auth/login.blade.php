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
            background-color: #2D2D2D;
            width: 520px;
            height: 560px;
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
        }

        .login-status {
            width: 424px;
            margin: 0 auto 18px;
            padding: 12px 14px;
            font-size: 13px;
            line-height: 1.6;
            color: #f2ede2;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .login-field input {
            display: block;
            width: 424px;
            height: 56px;
            box-sizing: border-box;
            background: #3A3A3A;
            border: 1px solid transparent;
            outline: none;
            box-shadow: none;
            color: #F5F5F5;
            padding: 0 16px;
            font-size: 16px;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .login-field input:focus {
            outline: none;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: none;
            background: #404040;
        }

        .login-field input:-webkit-autofill,
        .login-field input:-webkit-autofill:hover,
        .login-field input:-webkit-autofill:focus,
        .login-field input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #202020 inset !important;
            -webkit-text-fill-color: #F5F5F5 !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .login-field {
            width: 424px;
            margin: 0 auto 14px;
        }

        .login-field label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f4f4f4;
        }

        .login-error {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.5;
            color: #eab1b1;
        }

        .login-remember {
            width: 424px;
            margin: 34px auto 0;
        }

        .login-remember label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.78);
        }

        .login-forgot {
            text-align: center;
            margin-top: 42px;
        }

        .login-forgot a {
            font-size: 16px;
            color: white;
            transition: opacity 0.2s ease;
        }

        .login-forgot a:hover {
            opacity: 0.88;
        }

        .login-button {
            display: block;
            margin: 0 auto;
            width: 260px;
            height: 52px;
            margin-top: 32px;
            font-size: 18px;
            font-weight: 700;
            border: none;
            background: #e9e9e9;
            color: #1E1E1E;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 0 18px rgba(255, 255, 255, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 22px rgba(255, 255, 255, 0.22);
        }

        #remember_me {
            appearance: none;
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            background: #3A3A3A;
            border: 1px solid #8A8A8A;
            cursor: pointer;
            position: relative;
        }

        #remember_me:checked {
            background: #F2EDE2;
            border-color: #F2EDE2;
        }

        #remember_me:checked::after {
            content: "";
            position: absolute;
            top: 1px;
            left: 4px;
            width: 3px;
            height: 7px;
            border: solid #1E1E1E;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        @media (max-width: 640px) {
            .login-page {
                padding: 112px 14px 24px;
            }

            .login-box {
                width: 100%;
                height: auto;
                padding: 18px 0 28px;
            }

            .login-status,
            .login-field,
            .login-remember {
                width: calc(100% - 32px);
            }

            .login-field input {
                width: 100%;
                transition: background 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;

            }

            .login-field input:focus {
                transition: background 0.25s ease, border-color 0.25s ease;
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
                <div class="login-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="login-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username">
                    @error('email')
                        <div class="login-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="login-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-remember">
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <div class="login-forgot">
                        <a href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </div>
                @endif

                <button type="submit" class="login-button">
                    Log in
                </button>
            </form>
        </div>
    </div>
@endsection
