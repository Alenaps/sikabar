<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends \Illuminate\Database\Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User Biasa',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
