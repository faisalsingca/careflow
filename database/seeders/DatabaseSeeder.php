<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@careflow.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create doctor user with linked doctor profile
        $doctorUser = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@careflow.com',
            'password' => bcrypt('password'),
            'role' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $doctorUser->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'specialization' => 'Cardiology',
            'contact_number' => '09123456789',
            'email' => 'doctor@careflow.com',
            'status' => 'Active',
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@careflow.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        // Create patient user with linked patient profile
        $patientUser = User::create([
            'name' => 'Jane Smith',
            'email' => 'patient@example.com',
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        Patient::create([
            'user_id' => $patientUser->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'date_of_birth' => '1990-05-15',
            'gender' => 'Female',
            'contact_number' => '0987654321',
            'address' => '123 Main St',
            'notes' => 'New patient',
        ]);
    }
}