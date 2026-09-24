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
            'type' => ['required', 'in:TEMP,EXIT_SCHOOL'], // TEMP = Keluar Sementara, EXIT_SCHOOL = Pulang
        ]);

        $student = $request->user();

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
        $permit = PermitRequest::with('student')
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

        $requests = PermitRequest::with(['student:user_id,name,username,class_name'])
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
     * Menghasilkan QR Token unik dan durasi waktu kembali (expiry_time)
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

        // Tentukan batas waktu jika izin keluar sementara (misal: 30 menit)
        $expiryTime = null;
        if ($permit->type === 'TEMP') {
            $expiryTime = Carbon::now()->addMinutes(30);
        }

        $permit->update([
            'status' => 'ACTIVE',
            'qr_token' => Str::uuid()->toString(), // Token unik untuk di-render jadi QR Code di HP siswa
            'expiry_time' => $expiryTime,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil disetujui. QR Code telah digenerate.',
            'data' => $permit,
        ]);
    }

    /**
     * 6. Guru menyelesaikan izin (Siswa kembali ke kelas) atau menandai Alpha
     */
    public function resolve(Request $request, $id)
    {
        $request->validate([
            'action' => ['required', 'in:COMPLETED,ALPHA'],
        ]);

        $permit = PermitRequest::findOrFail($id);

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