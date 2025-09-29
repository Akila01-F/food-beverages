<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@foodbeverage.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@foodbeverage.com',
                'password' => Hash::make('1234'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a few test regular users
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Customer',
                'username' => 'customer',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
