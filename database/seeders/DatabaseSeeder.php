<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Create ADMIN Account ---
        // We check if the user exists first so we don't get an error running it twice
        $admin = User::where('email', '0000')->first();

        if (!$admin) {
            User::create([
                'name'     => 'Super Admin',      // Name is usually required
                'email'    => '0000',             // Admin Login ID
                'password' => Hash::make('""@!'), // Admin Password: ""@!
                'role'     => 'admin'             // Admin Role
            ]);

            // This prints a message in your terminal when it works
            $this->command->info('Admin account created: User 0000');
        } else {
            $this->command->info('Admin account already exists.');
        }

        // --- 2. Default Test User (Optional) ---
        // This checks if the test user exists to prevent errors
        $testUser = User::where('email', 'test@example.com')->first();
        if (!$testUser) {
            // User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
            //     'role' => 'student', // Default role if you use factory
            //     'password' => Hash::make('password'),
            // ]);
        }
    }
}
