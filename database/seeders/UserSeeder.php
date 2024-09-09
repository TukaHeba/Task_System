<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'manager',
            'email' => 'manager@gmail.com',
            'password' => '12345678',
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user1@gmail.com',
            'password' => '12345678',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user2@gmail.com',
            'password' => '12345678',
            'role' => 'user',
        ]);
    }
}
