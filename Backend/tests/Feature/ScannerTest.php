<?php

namespace Tests\Feature;

use App\Models\Request as PermitRequest;
use App\Models\User;
use Tests\TestCase;

class ScannerTest extends TestCase
{
    public function test_scanner_handles_valid_scan_and_prevents_duplicate_scan(): void
    {
        $student = User::where('role', 'STUDENT')->first();
        if (!$student) {
            $student = User::factory()->create(['role' => 'STUDENT', 'name' => 'Test Siswa', 'username' => 'siswa123']);
        }

        $teacher = User::where('role', 'TEACHER')->first();
        $satpam = User::where('role', 'SATPAM')->first();
        $testToken = 'QR-TEST-' . uniqid();

        // Clean up previous token if exists
        PermitRequest::where('qr_token', 'like', 'QR-TEST%')->delete();

        $permit = PermitRequest::create([
            'student_id' => $student->user_id,
            'initial_teacher_id' => $teacher ? $teacher->user_id : $student->user_id,
            'type' => 'EXIT_SCHOOL',
            'reason' => 'Izin pulang awal',
            'duration_minutes' => 0,
            'status' => 'APPROVED',
            'qr_token' => $testToken,
        ]);

        try {
            // Authenticate as SATPAM
            $this->actingAs($satpam);

            // First scan: should succeed
            $response1 = $this->postJson('/api/satpam/scan', [
                'qr_token' => $testToken,
            ]);

            $response1->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'scan_action' => 'EXIT',
                ]);

            // Second scan: permit is now CLOSED, should return 400 with formatted data
            $response2 = $this->postJson('/api/satpam/scan', [
                'qr_token' => $testToken,
            ]);

            $response2->assertStatus(400)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Surat izin ini sudah selesai/ditutup sebelumnya.',
                ])
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'request_id',
                        'type',
                        'status',
                        'student' => ['nis', 'name', 'class_name'],
                    ],
                ]);
        } finally {
            $permit->delete();
        }
    }
}
