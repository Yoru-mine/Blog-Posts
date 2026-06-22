@extends('layouts.app')

@section('title', 'Reset password')

@section('content')
    <style>
        body {
            background-image: url('{{ asset('images/gradient-back.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .auth-page {
            min-height: 100vh;
            padding: 118px 24px 32px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-box {
            background-color: #2D2D2D;
            width: 560px;
            min-height: 700px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.80);
            padding: 22px 0 32px;
        }

        .auth-title {
            font-size: 32px;
            text-align: center;
            margin: 0 0 26px;
            font-weight: 700;
        }

        .auth-field {
            width: 424px;
            margin: 0 auto 14px;
        }

        .auth-field label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #f4f4f4;
        }

        .auth-field input {
            display: block;
            width: 100%;
            height: 56px;
            box-sizing: border-box;
            background: #202020;
            border: 1px solid transparent;
            outline: none;
            color: #F5F5F5;
            padding: 0 16px;
            font-size: 16px;
            transition: background 0.25s ease, border-color 0.25s ease, color 0.25s ease;
        }

        .auth-field input:focus {
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .auth-error {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.5;
            color: #eab1b1;
        }

        .auth-button {
            display: block;
            margin: 28px auto 0;
            width: 260px;
            height: 52px;
            font-size: 16px;
            font-weight: 700;
            border: none;
            background: #e9e9e9;
            color: #1E1E1E;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 0 18px rgba(255, 255, 255, 0.18);
        }
    </style>

    <div class="auth-page">
        <div class="auth-box">
            <h1 class="auth-title">Reset password</h1>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-button">Reset password</button>
            </form>
        </div>
    </div>
@endsection
