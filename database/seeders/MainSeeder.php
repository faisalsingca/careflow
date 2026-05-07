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

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 15 PATIENTS ====================
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
            ['first_name' => 'Andres',   'last_name' => 'Bonifacio',  'dob' => '1991-05-12', 'gender' => 'Male',   'contact' => '09181234511', 'address' => 'Caloocan'],
            ['first_name' => 'Melchora', 'last_name' => 'Aquino',     'dob' => '1987-10-03', 'gender' => 'Female', 'contact' => '09181234512', 'address' => 'Malolos'],
            ['first_name' => 'Emilio',   'last_name' => 'Aguinaldo',  'dob' => '1979-03-22', 'gender' => 'Male',   'contact' => '09181234513', 'address' => 'Kawit Cavite'],
            ['first_name' => 'Gabriela', 'last_name' => 'Silang',     'dob' => '1994-07-19', 'gender' => 'Female', 'contact' => '09181234514', 'address' => 'Ilocos Sur'],
            ['first_name' => 'Lapu',     'last_name' => 'Lapu',       'dob' => '1998-04-27', 'gender' => 'Male',   'contact' => '09181234515', 'address' => 'Mactan Cebu'],
        ];

        $patients = [];
        foreach ($patientData as $d) {
            $patients[] = Patient::firstOrCreate(['contact_number' => $d['contact']], [
                'first_name'    => $d['first_name'],
                'last_name'     => $d['last_name'],
                'date_of_birth' => $d['dob'],
                'gender'        => $d['gender'],
                'address'       => $d['address'],
                'notes'         => null,
            ]);
        }

        // ==================== 15 DOCTORS ====================
        $doctorData = [
            ['first_name' => 'James',    'last_name' => 'Santos',     'specialization' => 'Cardiologist',    'contact' => '09171234501', 'email' => 'james.santos@careflow.com'],
            ['first_name' => 'Maria',    'last_name' => 'Cruz',       'specialization' => 'Pediatrician',    'contact' => '09171234502', 'email' => 'maria.cruz@careflow.com'],
            ['first_name' => 'Roberto',  'last_name' => 'Reyes',      'specialization' => 'Dermatologist',   'contact' => '09171234503', 'email' => 'roberto.reyes@careflow.com'],
            ['first_name' => 'Anna',     'last_name' => 'Mendoza',    'specialization' => 'Neurologist',     'contact' => '09171234504', 'email' => 'anna.mendoza@careflow.com'],
            ['first_name' => 'Carlos',   'last_name' => 'Garcia',     'specialization' => 'Orthopedic',      'contact' => '09171234505', 'email' => 'carlos.garcia@careflow.com'],
            ['first_name' => 'Liza',     'last_name' => 'Torres',     'specialization' => 'OB-GYN',          'contact' => '09171234506', 'email' => 'liza.torres@careflow.com'],
            ['first_name' => 'Miguel',   'last_name' => 'Ramos',      'specialization' => 'ENT Specialist',  'contact' => '09171234507', 'email' => 'miguel.ramos@careflow.com'],
            ['first_name' => 'Patricia', 'last_name' => 'Villanueva', 'specialization' => 'Ophthalmologist', 'contact' => '09171234508', 'email' => 'patricia.villanueva@careflow.com'],
            ['first_name' => 'Eduardo',  'last_name' => 'Flores',     'specialization' => 'General Surgeon', 'contact' => '09171234509', 'email' => 'eduardo.flores@careflow.com'],
            ['first_name' => 'Sophia',   'last_name' => 'Aquino',     'specialization' => 'Psychiatrist',    'contact' => '09171234510', 'email' => 'sophia.aquino@careflow.com'],
            ['first_name' => 'Rafael',   'last_name' => 'Bautista',   'specialization' => 'Urologist',       'contact' => '09171234511', 'email' => 'rafael.bautista@careflow.com'],
            ['first_name' => 'Teresa',   'last_name' => 'Navarro',    'specialization' => 'Endocrinologist', 'contact' => '09171234512', 'email' => 'teresa.navarro@careflow.com'],
            ['first_name' => 'Vincent',  'last_name' => 'Castillo',   'specialization' => 'Pulmonologist',   'contact' => '09171234513', 'email' => 'vincent.castillo@careflow.com'],
            ['first_name' => 'Marisol',  'last_name' => 'Dela Cruz',  'specialization' => 'Rheumatologist',  'contact' => '09171234514', 'email' => 'marisol.delacruz@careflow.com'],
            ['first_name' => 'Bernard',  'last_name' => 'Pascual',    'specialization' => 'Nephrologist',    'contact' => '09171234515', 'email' => 'bernard.pascual@careflow.com'],
        ];

        $doctors = [];
        foreach ($doctorData as $d) {
            $user = User::firstOrCreate(['email' => $d['email']], [
                'name'     => $d['first_name'] . ' ' . $d['last_name'],
                'password' => Hash::make('password'),
                'role'     => 'doctor',
            ]);
            $doctors[] = Doctor::firstOrCreate(['email' => $d['email']], [
                'user_id'        => $user->id,
                'first_name'     => $d['first_name'],
                'last_name'      => $d['last_name'],
                'specialization' => $d['specialization'],
                'contact_number' => $d['contact'],
                'status'         => 'Active',
            ]);
        }

        // ==================== 15 APPOINTMENTS ====================
        $statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
        $reasons  = [
            'Regular check-up', 'Fever and cough', 'Back pain',
            'Skin rash', 'Headache', 'Eye examination',
            'Stomach pain', 'Prenatal check-up', 'Joint pain',
            'Anxiety consultation', 'Blood pressure check',
            'Diabetes follow-up', 'Ear pain', 'Vision problem', 'Kidney stone',
        ];

        for ($i = 0; $i < 15; $i++) {
            Appointment::firstOrCreate(
                ['patient_id' => $patients[$i]->id, 'appointment_date' => now()->addDays($i - 5)->format('Y-m-d')],
                [
                    'doctor_id'        => $doctors[$i]->id,
                    'appointment_time' => sprintf('%02d:00:00', 8 + ($i % 10)),
                    'reason'           => $reasons[$i],
                    'status'           => $statuses[$i % 4],
                    'notes'            => null,
                ]
            );
        }

        // ==================== 15 MEDICAL RECORDS ====================
        $diagnoses = [
            'Hypertension Stage 1', 'Upper Respiratory Tract Infection',
            'Lumbar Disc Herniation', 'Atopic Dermatitis', 'Migraine',
            'Myopia', 'Acute Gastroenteritis', 'Gestational Diabetes',
            'Osteoarthritis', 'Generalized Anxiety Disorder',
            'Urinary Tract Infection', 'Hypothyroidism',
            'Chronic Obstructive Pulmonary Disease', 'Rheumatoid Arthritis', 'Chronic Kidney Disease',
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
            'Ciprofloxacin 500mg twice daily for 7 days',
            'Levothyroxine 50mcg once daily',
            'Salbutamol inhaler as needed',
            'Methotrexate 7.5mg weekly',
            'Losartan 50mg once daily',
        ];

        for ($i = 0; $i < 15; $i++) {
            MedicalRecord::firstOrCreate(
                ['patient_id' => $patients[$i]->id, 'record_date' => now()->subDays($i + 1)->format('Y-m-d')],
                [
                    'doctor_id'    => $doctors[$i]->id,
                    'diagnosis'    => $diagnoses[$i],
                    'prescription' => $prescriptions[$i],
                    'notes'        => 'Follow up after 2 weeks.',
                ]
            );
        }

        // ==================== 15 BILLINGS ====================
        $descriptions = [
            'Consultation Fee', 'Laboratory Tests', 'X-Ray Procedure',
            'Skin Treatment', 'MRI Scan', 'Eye Glasses Prescription',
            'IV Fluids and Medicines', 'Prenatal Package', 'Physical Therapy Session',
            'Psychiatric Evaluation', 'Urinalysis and UTZ', 'Thyroid Function Test',
            'Pulmonary Function Test', 'Rheumatology Consult', 'Kidney Dialysis Session',
        ];

        $amounts   = [500, 1500, 2500, 800, 5000, 3500, 2000, 4500, 1200, 3000, 900, 1800, 2200, 2800, 6000];
        $docPcts   = [30, 40, 35, 30, 40, 35, 30, 40, 35, 30, 35, 40, 30, 35, 40];
        $bStatuses = ['Paid','Unpaid','Paid','Unpaid','Paid','Paid','Unpaid','Paid','Unpaid','Cancelled','Paid','Unpaid','Paid','Paid','Unpaid'];

        for ($i = 0; $i < 15; $i++) {
            $amount      = $amounts[$i];
            $docPct      = $docPcts[$i];
            $docShare    = round($amount * $docPct / 100, 2);
            $clinicShare = round($amount - $docShare, 2);

            Billing::firstOrCreate(
                ['patient_id' => $patients[$i]->id, 'billing_date' => now()->subDays($i)->format('Y-m-d')],
                [
                    'doctor_id'      => $doctors[$i]->id,
                    'description'    => $descriptions[$i],
                    'amount'         => $amount,
                    'doctor_percent' => $docPct,
                    'doctor_share'   => $docShare,
                    'clinic_share'   => $clinicShare,
                    'status'         => $bStatuses[$i],
                    'notes'          => null,
                ]
            );
        }

        $this->command->info('✅ MainSeeder: 15 patients, 15 doctors, 15 appointments, 15 medical records, 15 billings inserted.');
    }
}