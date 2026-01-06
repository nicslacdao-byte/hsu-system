<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create SUPER ADMIN (User 0000)
        $admin = User::where('email', '0000')->first();

        if (!$admin) {
            User::create([
                'name'     => 'Super Admin',
                'email'    => '0000',             // Login ID
                'password' => Hash::make('""@!'), // Password
                'role'     => 'admin',            // Role
            ]);
            $this->command->info('Admin (0000) created successfully.');
        } else {
            $this->command->info('Admin (0000) already exists.');
        }
    }
}
