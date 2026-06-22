@extends('layouts.app')

@section('title', 'Manage users')

@section('content')
    <style>
        .admin-list-page {
            min-height: 100vh;
            padding: 132px 24px 72px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 22%),
                radial-gradient(circle at right center, rgba(255, 220, 220, 0.08), transparent 22%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 32%);
        }

        .admin-list-shell {
            max-width: 1260px;
            margin: 0 auto;
            display: grid;
            gap: 26px;
        }

        .admin-list-hero,
        .admin-table-card {
            background: rgba(30, 30, 30, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .admin-list-hero {
            padding: 34px;
        }

        .admin-list-title {
            margin: 0 0 12px;
            font-size: 42px;
        }

        .admin-list-copy {
            margin: 0;
            font-size: 16px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.75);
        }

        .admin-table-card {
            overflow: hidden;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 18px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            vertical-align: middle;
        }

        .admin-table th {
            font-size: 13px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.54);
        }

        .admin-table td {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.82);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            padding: 0 12px;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.08);
            color: #f4f4f4;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .role-badge.admin {
            background: #f2ede2;
            color: #1E1E1E;
            border-color: transparent;
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.06);
            color: #f4f4f4;
            font-family: inherit;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .admin-list-page {
                padding: 112px 14px 36px;
            }

            .admin-list-hero {
                padding: 22px 18px;
            }

            .admin-table-card {
                overflow-x: auto;
            }
        }
    </style>

    <div class="admin-list-page">
        <div class="admin-list-shell">
            <section class="admin-list-hero">
                <h1 class="admin-list-title">Manage users</h1>
                <p class="admin-list-copy">A simple user list with email addresses and role visibility so you can quickly see who has administrator access.</p>
            </section>

            <section class="admin-table-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="role-badge {{ $user->is_admin ? 'admin' : '' }}">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @if (auth()->id() !== $user->id)
                                        <div class="admin-actions">
                                            <form
                                                action="{{ route('admin.users.toggle-admin', $user->id) }}"
                                                method="POST"
                                                data-confirm
                                                data-confirm-title="{{ $user->is_admin ? 'Remove admin role?' : 'Make this user an admin?' }}"
                                                data-confirm-message="{{ $user->is_admin ? 'This user will lose administrator access.' : 'This user will gain administrator access.' }}"
                                                data-confirm-button="{{ $user->is_admin ? 'Remove admin' : 'Make admin' }}"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="admin-action-button">
                                                    {{ $user->is_admin ? 'Remove admin' : 'Make admin' }}
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="font-size: 13px; color: rgba(255,255,255,0.52);">Current account</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>

            @if (method_exists($users, 'links'))
                {{ $users->onEachSide(1)->links('partials.pagination') }}
            @endif
        </div>
    </div>
@endsection
