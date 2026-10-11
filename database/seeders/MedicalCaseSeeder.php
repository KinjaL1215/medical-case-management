<?php

namespace Database\Seeders;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicalCaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'Admin@12345',
                'role' => 'Admin',
            ]
        );

        // Create Viewer user
        User::updateOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => 'Viewer@12345',
                'role' => 'Viewer',
            ]
        );

        $statuses = [
            'Draft',
            'Planning',
            'In Review',
            'Approved',
            'Completed',
            'Cancelled',
        ];

        $priorities = ['Low', 'Medium', 'High'];

        // Create 20 dummy medical cases
        for ($i = 1; $i <= 20; $i++) {
            MedicalCase::updateOrCreate(
                ['case_number' => 'CASE-' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'patient_reference' => 'PAT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'surgeon_name' => 'Dr. Surgeon ' . $i,
                    'implant_type' => ['Hip', 'Knee', 'Spine'][$i % 3],
                    'status' => $statuses[($i - 1) % count($statuses)],
                    'priority' => $priorities[($i - 1) % count($priorities)],
                    'surgery_date' => now()->addDays($i)->toDateString(),
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
