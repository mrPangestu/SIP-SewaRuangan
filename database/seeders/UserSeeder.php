<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id' => Str::uuid(),
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'User Satu',
                'email' => 'user1@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'User Dua',
                'email' => 'user2@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'User Tiga',
                'email' => 'user3@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '081234567893',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'User Empat',
                'email' => 'user4@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '081234567894',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}