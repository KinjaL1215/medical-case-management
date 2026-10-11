<?php

namespace Tests\Feature;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MedicalCaseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'Admin@12345',
            'role' => 'Admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'Admin@12345',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'Admin@12345',
            'role' => 'Admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertUnauthorized()
            ->assertJsonPath('success', false);
    }

    public function test_guest_cannot_access_protected_api(): void
    {
        $response = $this->getJson('/api/cases');

        $response->assertUnauthorized();
    }

    public function test_admin_can_create_medical_case(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/cases', [
            'case_number' => 'CASE-TEST-001',
            'patient_reference' => 'PAT-TEST-001',
            'surgeon_name' => 'Dr. Test',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'High',
            'surgery_date' => '2026-11-15',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('cases', [
            'case_number' => 'CASE-TEST-001',
        ]);
    }

    public function test_duplicate_case_number_is_rejected(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        MedicalCase::create([
            'case_number' => 'CASE-DUPLICATE',
            'patient_reference' => 'PAT-OLD',
            'surgeon_name' => 'Dr. Old',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'Medium',
            'created_by' => $admin->id,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/cases', [
            'case_number' => 'CASE-DUPLICATE',
            'patient_reference' => 'PAT-NEW',
            'surgeon_name' => 'Dr. New',
            'implant_type' => 'Hip',
            'status' => 'Draft',
            'priority' => 'Low',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['case_number']);
    }

    public function test_viewer_cannot_delete_medical_case(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $medicalCase = MedicalCase::create([
            'case_number' => 'CASE-VIEWER-001',
            'patient_reference' => 'PAT-VIEWER-001',
            'surgeon_name' => 'Dr. Test',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'Medium',
            'created_by' => $admin->id,
        ]);

        $viewer = User::factory()->create([
            'role' => 'Viewer',
        ]);

        Sanctum::actingAs($viewer);

        $response = $this->deleteJson('/api/cases/' . $medicalCase->id);

        $response->assertForbidden();

        $this->assertDatabaseHas('cases', [
            'id' => $medicalCase->id,
        ]);
    }
}
