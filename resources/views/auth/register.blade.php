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
            background-color: #2D2D2D;
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
        }

        .register-field {
            width: 424px;
            margin: 0 auto 14px;
        }

        .register-field label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f4f4f4;
        }

        .register-field input {
            display: block;
            width: 424px;
            height: 56px;
            box-sizing: border-box;
            background: #202020;
            border: 1px solid transparent;
            outline: none;
            box-shadow: none;
            color: #F5F5F5;
            padding: 0 16px;
            font-size: 16px;
            transition: background 0.25s ease, border-color 0.25s ease, color 0.25s ease;
        }

        .register-field input:focus {
            outline: none;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: none;
            background: #202020;
        }

        .register-field input:-webkit-autofill,
        .register-field input:-webkit-autofill:hover,
        .register-field input:-webkit-autofill:focus,
        .register-field input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #202020 inset !important;
            -webkit-text-fill-color: #F5F5F5 !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        .register-error {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.5;
            color: #eab1b1;
        }

        .register-login {
            text-align: center;
            margin-top: 34px;
        }

        .register-login a {
            font-size: 16px;
            color: white;
            transition: opacity 0.2s ease;
        }

        .register-login a:hover {
            opacity: 0.88;
        }

        .register-button {
            display: block;
            margin: 32px auto 0;
            width: 260px;
            height: 52px;
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

        .register-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 22px rgba(255, 255, 255, 0.22);
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
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="register-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="register-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="register-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="register-error">{{ $message }}</div>
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
@endsection
