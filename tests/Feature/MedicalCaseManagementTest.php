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

    private function createMedicalCase(
        User $admin,
        string $caseNumber = 'CASE-TEST-001'
    ): MedicalCase {
        return MedicalCase::create([
            'case_number' => $caseNumber,
            'patient_reference' => 'PAT-TEST-001',
            'surgeon_name' => 'Dr. Test',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'Medium',
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
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
        $this->getJson('/api/cases')->assertUnauthorized();
    }

    public function test_admin_can_create_medical_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
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
        $admin = User::factory()->create(['role' => 'Admin']);
        $this->createMedicalCase($admin, 'CASE-DUPLICATE');

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

    public function test_viewer_can_view_medical_cases(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase($admin);

        Sanctum::actingAs(
            User::factory()->create(['role' => 'Viewer'])
        );

        $this->getJson('/api/cases')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->getJson('/api/cases/' . $medicalCase->id)
            ->assertOk()
            ->assertJsonPath('data.id', $medicalCase->id);
    }

    public function test_viewer_cannot_create_medical_case(): void
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'Viewer'])
        );

        $this->postJson('/api/cases', [
            'case_number' => 'CASE-VIEWER-CREATE',
            'patient_reference' => 'PAT-001',
            'surgeon_name' => 'Dr. Test',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'Medium',
        ])->assertForbidden();

        $this->assertDatabaseMissing('cases', [
            'case_number' => 'CASE-VIEWER-CREATE',
        ]);
    }

    public function test_admin_can_update_medical_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase($admin);

        Sanctum::actingAs($admin);

        $this->putJson('/api/cases/' . $medicalCase->id, [
            'case_number' => $medicalCase->case_number,
            'patient_reference' => $medicalCase->patient_reference,
            'surgeon_name' => 'Dr. Updated',
            'implant_type' => 'Hip',
            'status' => 'Approved',
            'priority' => 'High',
        ])->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('cases', [
            'id' => $medicalCase->id,
            'surgeon_name' => 'Dr. Updated',
            'status' => 'Approved',
        ]);
    }

    public function test_viewer_cannot_update_medical_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase($admin);

        Sanctum::actingAs(
            User::factory()->create(['role' => 'Viewer'])
        );

        $this->putJson('/api/cases/' . $medicalCase->id, [
            'case_number' => $medicalCase->case_number,
            'patient_reference' => $medicalCase->patient_reference,
            'surgeon_name' => 'Dr. Unauthorized',
            'implant_type' => 'Knee',
            'status' => 'Planning',
            'priority' => 'Medium',
        ])->assertForbidden();

        $this->assertDatabaseMissing('cases', [
            'id' => $medicalCase->id,
            'surgeon_name' => 'Dr. Unauthorized',
        ]);
    }

    public function test_viewer_cannot_delete_medical_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase(
            $admin,
            'CASE-VIEWER-DELETE'
        );

        Sanctum::actingAs(
            User::factory()->create(['role' => 'Viewer'])
        );

        $this->deleteJson('/api/cases/' . $medicalCase->id)
            ->assertForbidden();

        $this->assertDatabaseHas('cases', [
            'id' => $medicalCase->id,
        ]);
    }

    public function test_admin_can_delete_medical_case(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase(
            $admin,
            'CASE-ADMIN-DELETE'
        );

        Sanctum::actingAs($admin);

        $this->deleteJson('/api/cases/' . $medicalCase->id)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('cases', [
            'id' => $medicalCase->id,
        ]);
    }

    public function test_api_logout_revokes_current_token(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $token = $admin->createToken('test-token')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_viewer_cannot_access_web_create_or_edit_pages(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase(
            $admin,
            'CASE-WEB-001'
        );

        $viewer = User::factory()->create(['role' => 'Viewer']);

        $this->actingAs($viewer)
            ->get('/cases/create')
            ->assertForbidden();

        $this->actingAs($viewer)
            ->get('/cases/' . $medicalCase->id . '/edit')
            ->assertForbidden();
    }

    public function test_viewer_can_view_web_case_list_and_details(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $medicalCase = $this->createMedicalCase(
            $admin,
            'CASE-WEB-002'
        );

        $viewer = User::factory()->create(['role' => 'Viewer']);

        $this->actingAs($viewer)
            ->get('/cases')
            ->assertOk();

        $this->actingAs($viewer)
            ->get('/cases/' . $medicalCase->id)
            ->assertOk();
    }
}
