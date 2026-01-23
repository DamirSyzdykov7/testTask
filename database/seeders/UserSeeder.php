<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'Администратор',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $users = [
            [
                'name' => 'Менеджер Иванов',
                'email' => 'manager@example.com',
                'password' => Hash::make('manager123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Поддержка Петрова',
                'email' => 'support@example.com',
                'password' => Hash::make('support123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
