<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfessorDemoSeeder extends Seeder
{
    public function run(): void
    {
        $doctorOne = $this->doctorWithLogin(
            'nolea@gmail.com',
            'Nolea',
            'Demo',
            'Family Medicine',
            '09170000001'
        );

        $doctorTwo = $this->doctorWithLogin(
            'shane@gmail.com',
            'Shane',
            'Demo',
            'Internal Medicine',
            '09170000002'
        );

        $maria = $this->patient(
            'Maria',
            'Demo',
            '1993-04-12',
            'Female',
            '09990000001',
            'Davao City',
            'Demo patient with two visits under Dr. Nolea Demo.'
        );

        $john = $this->patient(
            'John',
            'Sample',
            '1988-09-21',
            'Male',
            '09990000002',
            'Cebu City',
            'Demo patient assigned to Dr. Sofia Santos.'
        );

        $ana = $this->patient(
            'Ana',
            'Professor',
            '1979-02-05',
            'Female',
            '09990000003',
            'Manila',
            'Demo patient assigned only to Dr. Andrew Reyes.'
        );

        $this->completedVisit(
            'DEMO-NOLEA-MARIA-1',
            $maria,
            $doctorOne,
            now()->subDays(10)->toDateString(),
            '09:00:00',
            'Fever and cough',
            'Upper respiratory tract infection',
            'Paracetamol 500mg every 6 hours as needed; fluids and rest.',
            'First completed visit. This record belongs only to nolea@gmail.com.'
        );

        $this->completedVisit(
            'DEMO-NOLEA-MARIA-2',
            $maria,
            $doctorOne,
            now()->subDays(2)->toDateString(),
            '10:30:00',
            'Follow-up consultation',
            'Improving respiratory infection',
            'Continue supportive care for 3 days.',
            'Second record for the same patient and same doctor.'
        );

        $this->completedVisit(
            'DEMO-NOLEA-JOHN-1',
            $john,
            $doctorOne,
            now()->subDays(1)->toDateString(),
            '13:00:00',
            'Blood pressure check',
            'Hypertension Stage 1',
            'Amlodipine 5mg once daily.',
            'Assigned to Dr. Nolea Demo for doctor-filter demo.'
        );

        $this->completedVisit(
            'DEMO-SHANE-ANA-1',
            $ana,
            $doctorTwo,
            now()->subDays(3)->toDateString(),
            '14:00:00',
            'Diabetes follow-up',
            'Type 2 Diabetes Mellitus',
            'Metformin 500mg twice daily with meals.',
            'This record should be visible to shane@gmail.com, admin, and staff.'
        );

        $this->billing($maria, $doctorOne, 'DEMO-NOLEA-MARIA-BILL', 750, 'Paid');
        $this->billing($john, $doctorOne, 'DEMO-NOLEA-JOHN-BILL', 650, 'Unpaid');
        $this->billing($ana, $doctorTwo, 'DEMO-SHANE-ANA-BILL', 900, 'Paid');

        $this->command->info('Professor demo data ready for nolea@gmail.com and shane@gmail.com. Existing usernames and passwords were not changed.');
    }

    private function doctorWithLogin(string $email, string $firstName, string $lastName, string $specialization, string $contact): Doctor
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->role !== 'doctor') {
                $user->update(['role' => 'doctor']);
            }
        } else {
            $user = User::create([
                'name' => $firstName.' '.$lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);

            $this->command->warn("Created missing doctor login {$email} with password: password");
        }

        $doctor = Doctor::where('email', $email)->first();

        if ($doctor) {
            $doctor->update([
                'user_id' => $user->id,
                'status' => 'Active',
            ]);

            return $doctor;
        }

        return Doctor::create([
            'user_id' => $user->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'specialization' => $specialization,
            'contact_number' => $contact,
            'email' => $email,
            'status' => 'Active',
        ]);
    }

    private function patient(string $firstName, string $lastName, string $dob, string $gender, string $contact, string $address, string $notes): Patient
    {
        return Patient::updateOrCreate(
            ['contact_number' => $contact],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $dob,
                'gender' => $gender,
                'address' => $address,
                'notes' => $notes,
            ]
        );
    }

    private function completedVisit(
        string $code,
        Patient $patient,
        Doctor $doctor,
        string $date,
        string $time,
        string $reason,
        string $diagnosis,
        string $prescription,
        string $notes
    ): void {
        $appointment = Appointment::updateOrCreate(
            ['notes' => $code],
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'reason' => $reason,
                'status' => 'Completed',
            ]
        );

        MedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'record_date' => $date,
                'diagnosis' => $diagnosis,
                'prescription' => $prescription,
                'notes' => $notes,
            ]
        );
    }

    private function billing(Patient $patient, Doctor $doctor, string $code, int $amount, string $status): void
    {
        $doctorPercent = 40;
        $doctorShare = round($amount * $doctorPercent / 100, 2);

        Billing::updateOrCreate(
            ['notes' => $code],
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'billing_date' => now()->toDateString(),
                'description' => 'Demo consultation fee',
                'amount' => $amount,
                'doctor_percent' => $doctorPercent,
                'doctor_share' => $doctorShare,
                'clinic_share' => round($amount - $doctorShare, 2),
                'status' => $status,
            ]
        );
    }
}
