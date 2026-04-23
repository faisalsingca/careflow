<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Only create admin if it doesn't exist
        if (!User::where('email', 'admin@clinic.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@clinic.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }
        
        // Only create staff if it doesn't exist
        if (!User::where('email', 'staff@clinic.com')->exists()) {
            User::create([
                'name' => 'Staff User',
                'email' => 'staff@clinic.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]);
        }
    }
}