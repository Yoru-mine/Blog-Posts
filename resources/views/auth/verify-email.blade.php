@extends('layouts.app')

@section('title', 'Verify email')

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
            width: 620px;
            min-height: 520px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.80);
            padding: 28px 0 32px;
        }

        .auth-title,
        .auth-copy,
        .auth-status,
        .auth-actions {
            width: 460px;
            margin-left: auto;
            margin-right: auto;
        }

        .auth-title {
            font-size: 32px;
            text-align: center;
            margin-bottom: 22px;
            font-weight: 700;
        }

        .auth-copy {
            font-size: 15px;
            line-height: 1.85;
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 20px;
        }

        .auth-status {
            padding: 12px 14px;
            margin-bottom: 24px;
            font-size: 13px;
            line-height: 1.6;
            color: #f2ede2;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .auth-actions {
            display: grid;
            gap: 16px;
        }

        .auth-button,
        .auth-link-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border: none;
            text-decoration: none;
            font-family: inherit;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .auth-button {
            background: #e9e9e9;
            color: #1E1E1E;
            box-shadow: 0 0 18px rgba(255, 255, 255, 0.18);
        }

        .auth-link-button {
            background: rgba(255, 255, 255, 0.08);
            color: #F5F5F5;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
    </style>

    <div class="auth-page">
        <div class="auth-box">
            <h1 class="auth-title">Verify email</h1>
            <p class="auth-copy">
                Before getting started, please verify your email address by clicking the link we sent you. If it did not arrive, you can request another one below.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="auth-status">A new verification link has been sent to your email address.</div>
            @endif

            <div class="auth-actions">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="auth-button" style="width: 100%;">Resend verification email</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-link-button" style="width: 100%;">Log out</button>
                </form>
            </div>
        </div>
    </div>
@endsection
