<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('newpassword123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $users = [
            ['name' => 'Alice Carter', 'username' => 'alice', 'email' => 'alice@example.com'],
            ['name' => 'Mia Turner', 'username' => 'mua', 'email' => 'mia@example.com'],
            ['name' => 'Noah Brooks', 'username' => 'noah', 'email' => 'noah@example.com'],
            ['name' => 'Ethan Reed', 'username' => 'ethan', 'email' => 'ethan@example.com'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
