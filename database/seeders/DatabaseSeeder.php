<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\MedicalRecord;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ADMIN ====================
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@careflow.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ==================== DOCTORS (10) ====================
        $doctorData = [
            ['first_name' => 'James',    'last_name' => 'Santos',    'specialization' => 'Cardiologist',      'contact_number' => '09171234501', 'email' => 'james.santos@careflow.com'],
            ['first_name' => 'Maria',    'last_name' => 'Cruz',      'specialization' => 'Pediatrician',      'contact_number' => '09171234502', 'email' => 'maria.cruz@careflow.com'],
            ['first_name' => 'Roberto',  'last_name' => 'Reyes',     'specialization' => 'Dermatologist',     'contact_number' => '09171234503', 'email' => 'roberto.reyes@careflow.com'],
            ['first_name' => 'Anna',     'last_name' => 'Mendoza',   'specialization' => 'Neurologist',       'contact_number' => '09171234504', 'email' => 'anna.mendoza@careflow.com'],
            ['first_name' => 'Carlos',   'last_name' => 'Garcia',    'specialization' => 'Orthopedic',        'contact_number' => '09171234505', 'email' => 'carlos.garcia@careflow.com'],
            ['first_name' => 'Liza',     'last_name' => 'Torres',    'specialization' => 'OB-GYN',            'contact_number' => '09171234506', 'email' => 'liza.torres@careflow.com'],
            ['first_name' => 'Miguel',   'last_name' => 'Ramos',     'specialization' => 'ENT Specialist',    'contact_number' => '09171234507', 'email' => 'miguel.ramos@careflow.com'],
            ['first_name' => 'Patricia', 'last_name' => 'Villanueva','specialization' => 'Ophthalmologist',   'contact_number' => '09171234508', 'email' => 'patricia.villanueva@careflow.com'],
            ['first_name' => 'Eduardo',  'last_name' => 'Flores',    'specialization' => 'General Surgeon',   'contact_number' => '09171234509', 'email' => 'eduardo.flores@careflow.com'],
            ['first_name' => 'Sophia',   'last_name' => 'Aquino',    'specialization' => 'Psychiatrist',      'contact_number' => '09171234510', 'email' => 'sophia.aquino@careflow.com'],
        ];

        $doctors = [];
        foreach ($doctorData as $data) {
            $user = User::create([
                'name'     => $data['first_name'] . ' ' . $data['last_name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'doctor',
            ]);
            $doctor = Doctor::create([
                'user_id'        => $user->id,
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'specialization' => $data['specialization'],
                'contact_number' => $data['contact_number'],
                'email'          => $data['email'],
                'status'         => 'Active',
            ]);
            $doctors[] = $doctor;
        }

        // ==================== STAFF (10) ====================
        $staffNames = [
            ['name' => 'Juan dela Cruz',    'email' => 'juan.staff@careflow.com'],
            ['name' => 'Rosa Bautista',     'email' => 'rosa.staff@careflow.com'],
            ['name' => 'Mark Salazar',      'email' => 'mark.staff@careflow.com'],
            ['name' => 'Cathy Domingo',     'email' => 'cathy.staff@careflow.com'],
            ['name' => 'Leo Navarro',       'email' => 'leo.staff@careflow.com'],
            ['name' => 'Nina Castillo',     'email' => 'nina.staff@careflow.com'],
            ['name' => 'Ryan Pascual',      'email' => 'ryan.staff@careflow.com'],
            ['name' => 'Grace Morales',     'email' => 'grace.staff@careflow.com'],
            ['name' => 'Dennis Lim',        'email' => 'dennis.staff@careflow.com'],
            ['name' => 'Joy Fernandez',     'email' => 'joy.staff@careflow.com'],
        ];

        foreach ($staffNames as $s) {
            User::create([
                'name'     => $s['name'],
                'email'    => $s['email'],
                'password' => Hash::make('password'),
                'role'     => 'staff',
            ]);
        }

        // ==================== PATIENTS (10) ====================
        $patientData = [
            ['first_name' => 'Pedro',    'last_name' => 'Dela Rosa',  'dob' => '1990-03-15', 'gender' => 'Male',   'contact' => '09181234501', 'address' => 'Davao City'],
            ['first_name' => 'Maria',    'last_name' => 'Santos',     'dob' => '1985-07-22', 'gender' => 'Female', 'contact' => '09181234502', 'address' => 'Cebu City'],
            ['first_name' => 'Jose',     'last_name' => 'Rizal',      'dob' => '1995-11-30', 'gender' => 'Male',   'contact' => '09181234503', 'address' => 'Manila'],
            ['first_name' => 'Ana',      'last_name' => 'Reyes',      'dob' => '2000-01-10', 'gender' => 'Female', 'contact' => '09181234504', 'address' => 'Makati City'],
            ['first_name' => 'Ramon',    'last_name' => 'Magsaysay',  'dob' => '1978-06-05', 'gender' => 'Male',   'contact' => '09181234505', 'address' => 'Quezon City'],
            ['first_name' => 'Lucia',    'last_name' => 'Tan',        'dob' => '1992-09-18', 'gender' => 'Female', 'contact' => '09181234506', 'address' => 'Pasig City'],
            ['first_name' => 'Antonio',  'last_name' => 'Luna',       'dob' => '1988-04-25', 'gender' => 'Male',   'contact' => '09181234507', 'address' => 'Taguig City'],
            ['first_name' => 'Carmen',   'last_name' => 'Guerrero',   'dob' => '1975-12-08', 'gender' => 'Female', 'contact' => '09181234508', 'address' => 'Mandaluyong'],
            ['first_name' => 'Fernando', 'last_name' => 'Poe',        'dob' => '2002-08-14', 'gender' => 'Male',   'contact' => '09181234509', 'address' => 'Las Pinas'],
            ['first_name' => 'Gloria',   'last_name' => 'Macapagal',  'dob' => '1983-02-28', 'gender' => 'Female', 'contact' => '09181234510', 'address' => 'Paranaque'],
        ];

        $patients = [];
        foreach ($patientData as $data) {
            $patient = Patient::create([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'date_of_birth'  => $data['dob'],
                'gender'         => $data['gender'],
                'contact_number' => $data['contact'],
                'address'        => $data['address'],
                'notes'          => null,
            ]);
            $patients[] = $patient;
        }

        // ==================== APPOINTMENTS (10) ====================
        $statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
        $reasons  = [
            'Regular check-up', 'Fever and cough', 'Back pain',
            'Skin rash', 'Headache', 'Eye examination',
            'Stomach pain', 'Prenatal check-up', 'Joint pain', 'Anxiety consultation'
        ];

        for ($i = 0; $i < 10; $i++) {
            Appointment::create([
                'patient_id'       => $patients[$i]->id,
                'doctor_id'        => $doctors[$i]->id,
                'appointment_date' => now()->addDays($i - 3)->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:00:00', 8 + $i),
                'reason'           => $reasons[$i],
                'status'           => $statuses[$i % 4],
                'notes'            => null,
            ]);
        }

        // ==================== MEDICAL RECORDS (10) ====================
        $diagnoses = [
            'Hypertension Stage 1',
            'Upper Respiratory Tract Infection',
            'Lumbar Disc Herniation',
            'Atopic Dermatitis',
            'Migraine',
            'Myopia',
            'Acute Gastroenteritis',
            'Gestational Diabetes',
            'Osteoarthritis',
            'Generalized Anxiety Disorder',
        ];

        $prescriptions = [
            'Amlodipine 5mg once daily',
            'Amoxicillin 500mg 3x daily for 7 days',
            'Ibuprofen 400mg 3x daily, physical therapy',
            'Hydrocortisone cream, antihistamine',
            'Sumatriptan 50mg as needed',
            'Corrective lenses -1.75 OD, -2.00 OS',
            'Oral rehydration salts, Loperamide',
            'Diet modification, insulin monitoring',
            'Celecoxib 200mg once daily',
            'Sertraline 50mg once daily',
        ];

        for ($i = 0; $i < 10; $i++) {
            MedicalRecord::create([
                'patient_id'   => $patients[$i]->id,
                'doctor_id'    => $doctors[$i]->id,
                'record_date'  => now()->subDays($i + 1)->format('Y-m-d'),
                'diagnosis'    => $diagnoses[$i],
                'prescription' => $prescriptions[$i],
                'notes'        => 'Follow up after 2 weeks.',
            ]);
        }

        // ==================== BILLINGS (10) ====================
        $descriptions = [
            'Consultation Fee',
            'Laboratory Tests',
            'X-Ray Procedure',
            'Skin Treatment',
            'MRI Scan',
            'Eye Glasses Prescription',
            'IV Fluids and Medicines',
            'Prenatal Package',
            'Physical Therapy Session',
            'Psychiatric Evaluation',
        ];

        $amounts  = [500, 1500, 2500, 800, 5000, 3500, 2000, 4500, 1200, 3000];
        $docPcts  = [30, 40, 35, 30, 40, 35, 30, 40, 35, 30];
        $bStatuses = ['Paid', 'Unpaid', 'Paid', 'Unpaid', 'Paid', 'Paid', 'Unpaid', 'Paid', 'Unpaid', 'Cancelled'];

        for ($i = 0; $i < 10; $i++) {
            $amount      = $amounts[$i];
            $docPct      = $docPcts[$i];
            $docShare    = round($amount * $docPct / 100, 2);
            $clinicShare = round($amount - $docShare, 2);

            Billing::create([
                'patient_id'     => $patients[$i]->id,
                'doctor_id'      => $doctors[$i]->id,
                'billing_date'   => now()->subDays($i)->format('Y-m-d'),
                'description'    => $descriptions[$i],
                'amount'         => $amount,
                'doctor_percent' => $docPct,
                'doctor_share'   => $docShare,
                'clinic_share'   => $clinicShare,
                'status'         => $bStatuses[$i],
                'notes'          => null,
            ]);
        }

        $this->command->info('✅ Seeded: 1 admin, 10 doctors, 10 staff, 10 patients, 10 appointments, 10 medical records, 10 billings.');
    }
}