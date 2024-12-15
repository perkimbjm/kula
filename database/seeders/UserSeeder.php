<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kularakat.web.id',
            'password' => 'sanggam123', // akan di-hash oleh mutator
            'role_id' => 1, // Sesuaikan dengan role yang ada
            'email_verified_at' => now(),
        ]);
    }
}