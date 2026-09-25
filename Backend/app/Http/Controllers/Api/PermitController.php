<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as PermitRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PermitController extends Controller
{
    // ==========================================
    // AREA SISWA (STUDENT)
    // ==========================================

    /**
     * 1. Mendapatkan daftar guru pengampu untuk dipilih di form izin
     */
    public function getTeachers()
    {
        $teachers = User::where('role', 'teacher')
            ->select('user_id', 'name', 'username')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $teachers,
        ]);
    }

    /**
     * 2. Siswa mengajukan surat izin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => ['required', 'exists:users,user_id'],
            'type' => ['required', 'in:TEMP,EXIT_SCHOOL'],
            'reason' => ['required', 'string', 'max:500'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:180'],
        ]);

        $student = $request->user();

        PermitRequest::syncOverdueStatuses();

        // Cegah siswa mengajukan izin baru jika masih punya izin PENDING atau ACTIVE
        $hasActivePermit = PermitRequest::where('student_id', $student->user_id)
            ->whereIn('status', ['PENDING', 'ACTIVE', 'OVERDUE'])
            ->exists();

        if ($hasActivePermit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda masih memiliki izin aktif atau sedang menunggu persetujuan.',
            ], 422);
        }

        $permit = PermitRequest::create([
            'student_id' => $student->user_id,
            'initial_teacher_id' => $request->teacher_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'duration_minutes' => $request->duration_minutes ?? 30,
            'status' => 'PENDING',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan izin berhasil dibuat. Menunggu persetujuan guru pengampu.',
            'data' => $permit,
        ], 201);
    }

    /**
     * 3. Siswa melihat status izin aktif miliknya (beserta token QR jika sudah di-approve)
     */
    public function myActivePermit(Request $request)
    {
        PermitRequest::syncOverdueStatuses();

        $permit = PermitRequest::with(['student', 'teacher'])
            ->where('student_id', $request->user()->user_id)
            ->whereIn('status', ['PENDING', 'ACTIVE', 'OVERDUE'])
            ->latest()
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $permit,
        ]);
    }

    // ==========================================
    // AREA GURU (TEACHER)
    // ==========================================

    /**
     * 4. Guru melihat daftar antrean izin yang butuh persetujuan
     */
    public function pendingRequests(Request $request)
    {
        $teacherId = $request->user()->user_id;

        $requests = PermitRequest::with(['student:user_id,name,username,class_name,email'])
            ->where('initial_teacher_id', $teacherId)
            ->where('status', 'PENDING')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ]);
    }

    /**
     * 5. Guru menyetujui izin (Approve)
     * Menghasilkan QR Token unik. Perhitungan waktu (expiry_time) dimulai saat discan satpam di pos gerbang.
     */
    public function approve(Request $request, $id)
    {
        $permit = PermitRequest::findOrFail($id);

        // Pastikan hanya guru yang dituju yang bisa melakukan approval
        if ($permit->initial_teacher_id !== $request->user()->user_id) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Anda tidak memiliki wewenang untuk menyetujui izin ini.',
            ], 403);
        }

        // Terbitkan QR Token. expiry_time dibiarkan null sampai divalidasi satpam di gerbang
        $permit->update([
            'status' => 'ACTIVE',
            'qr_token' => Str::uuid()->toString(),
            'expiry_time' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil disetujui. QR Code telah digenerate.',
            'data' => $permit,
        ]);
    }

    /**
     * 6. Guru menyelesaikan izin (Siswa kembali ke kelas), menolak (REJECTED), atau menandai Alpha
     */
    public function resolve(Request $request, $id)
    {
        $request->validate([
            'action' => ['required', 'in:COMPLETED,ALPHA,REJECTED'],
        ]);

        $permit = PermitRequest::findOrFail($id);

        // Check authorization
        if ($permit->initial_teacher_id !== $request->user()->user_id) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Anda tidak memiliki wewenang untuk mengubah status izin ini.',
            ], 403);
        }

        $permit->update([
            'status' => $request->action,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status izin berhasil diperbarui menjadi ' . $request->action,
            'data' => $permit,
        ]);
    }
}