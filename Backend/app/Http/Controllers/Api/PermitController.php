<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as PermitRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\Schedule;
use App\Models\Subject;

class PermitController extends Controller
{
    // ==========================================
    // AREA SISWA (STUDENT)
    // ==========================================

    /**
     * 1. Mendapatkan daftar guru pengampu dan guru jam pelajaran aktif
     */
    public function getTeachers(Request $request)
    {
        $student = $request->user();
        $studentClass = $student?->class_name ?? 'XII RPL 1';

        $teachers = User::where('role', 'teacher')
            ->select('user_id', 'name', 'username')
            ->get();

        if ($teachers->isEmpty()) {
            $teachers = collect([
                (object)['user_id' => 6, 'name' => 'Ahmad Dahlan, S.Pd.', 'username' => 'guru1'],
                (object)['user_id' => 7, 'name' => 'Ratna Dewi, M.Pd.', 'username' => 'guru2'],
                (object)['user_id' => 8, 'name' => 'Bambang Pamungkas, S.Kom', 'username' => 'guru3'],
            ]);
        }

        // Cari jadwal aktif untuk kelas siswa
        $schedule = Schedule::with(['teacher:user_id,name', 'subject'])
            ->where('class_name', $studentClass)
            ->first();

        $activeTeacherId = $schedule?->teacher_id ?? ($teachers->first()?->user_id ?? 6);
        $activeSubject = $schedule?->subject?->name ?? 'Pemrograman Web & Perangkat Bergerak (PWPB)';
        $activeRoom = $schedule?->room ?? 'Lab Komputer RPL 1';

        $teachersData = $teachers->map(function ($t) use ($activeTeacherId) {
            $isAuto = $t->user_id == $activeTeacherId;
            $subject = $t->user_id == 6 ? 'Pemrograman Web (PWPB)' : ($t->user_id == 8 ? 'Pemrograman Berorientasi Objek (PBO)' : 'Fisika Terapan');
            return [
                'user_id' => $t->user_id,
                'name' => $t->name,
                'username' => $t->username,
                'subject' => $subject,
                'is_current_schedule' => $isAuto,
                'display_label' => $t->name . ' (' . $subject . ')' . ($isAuto ? ' — [Jadwal Aktif]' : ''),
            ];
        });

        $activeTeacher = $teachers->firstWhere('user_id', $activeTeacherId) ?? $teachers->first();

        return response()->json([
            'status' => 'success',
            'data' => $teachersData,
            'current_schedule' => [
                'teacher_id' => $activeTeacher->user_id,
                'teacher_name' => $activeTeacher->name,
                'subject' => $activeSubject,
                'room' => $activeRoom,
                'class_name' => $studentClass,
                'period' => 'Jam Pelajaran Aktif (Sedang Berlangsung)',
            ]
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

        // Cegah siswa mengajukan izin baru jika masih punya izin PENDING, APPROVED, ACTIVE, atau OVERDUE
        $hasActivePermit = PermitRequest::where('student_id', $student->user_id)
            ->whereIn('status', ['PENDING', 'APPROVED', 'ACTIVE', 'OVERDUE'])
            ->exists();

        if ($hasActivePermit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda masih memiliki izin aktif atau sedang menunggu persetujuan.',
            ], 422);
        }

        // Cegah siswa jika tercatat ALPHA pada hari ini sebelum klarifikasi ke guru/BK
        $hasAlphaToday = PermitRequest::where('student_id', $student->user_id)
            ->where('status', 'ALPHA')
            ->where(function ($query) {
                $query->whereDate('alpha_at', Carbon::today())
                      ->orWhere(function ($q) {
                          $q->whereNull('alpha_at')
                            ->whereDate('updated_at', Carbon::today());
                      });
            })
            ->exists();

        if ($hasAlphaToday) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak dapat mengajukan izin baru karena tercatat ALPHA pada perizinan hari ini. Harap temui Guru Pengampu atau Guru BK.',
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
            ->whereIn('status', ['PENDING', 'APPROVED', 'ACTIVE', 'OVERDUE', 'ALPHA', 'REJECTED'])
            ->latest()
            ->first();

        // Jika status ALPHA atau REJECTED berasal dari sebelum hari ini, jangan tampilkan sebagai izin aktif
        if ($permit && in_array($permit->status, ['ALPHA', 'REJECTED'])) {
            $checkDate = $permit->alpha_at ? Carbon::parse($permit->alpha_at) : $permit->updated_at;
            if (!$checkDate->isToday()) {
                $permit = null;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $permit,
        ]);
    }

    /**
     * 3b. Siswa melihat seluruh riwayat perizinan miliknya (lampau / selesai)
     */
    public function myPermitHistory(Request $request)
    {
        PermitRequest::syncOverdueStatuses();

        $history = PermitRequest::with(['teacher:user_id,name,username'])
            ->where('student_id', $request->user()->user_id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $history,
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
     * Menghasilkan QR Token unik. Status menjadi APPROVED. Perhitungan waktu (expiry_time) dimulai saat discan satpam di pos gerbang.
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

        // Terbitkan QR Token. Status APPROVED. expiry_time dibiarkan null sampai divalidasi satpam di gerbang
        $permit->update([
            'status' => 'APPROVED',
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

        $permit = PermitRequest::with('student')->findOrFail($id);

        $teacherId = $request->user()->user_id;
        $isInitialTeacher = $permit->initial_teacher_id === $teacherId;
        $isClassTeacher = false;

        if ($permit->student?->class_name) {
            $isClassTeacher = Schedule::where('teacher_id', $teacherId)
                ->where('class_name', $permit->student->class_name)
                ->exists();
        }

        // Check authorization: Guru yang dituju (initial_teacher) ATAU guru pengajar rombel kelas siswa
        if (!$isInitialTeacher && !$isClassTeacher) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Anda tidak memiliki wewenang untuk mengubah status izin ini.',
            ], 403);
        }

        $updateData = [
            'status' => $request->action,
        ];

        if ($request->action === 'ALPHA') {
            $updateData['alpha_at'] = Carbon::now();
        }

        $permit->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Status izin berhasil diperbarui menjadi ' . $request->action,
            'data' => $permit,
        ]);
    }

    /**
     * 7. Siswa membatalkan permohonan izin (hanya jika masih PENDING atau APPROVED sebelum ke gerbang)
     */
    public function cancel(Request $request, $id)
    {
        $permit = PermitRequest::findOrFail($id);

        // Pastikan hanya pemilik izin yang bisa membatalkan
        if ($permit->student_id !== $request->user()->user_id) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Anda tidak memiliki hak akses untuk membatalkan izin ini.',
            ], 403);
        }

        // Hanya boleh dibatalkan jika belum aktif keluar gerbang
        if (!in_array($permit->status, ['PENDING', 'APPROVED'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya izin yang berstatus Menunggu Persetujuan atau Disetujui (sebelum melintasi gerbang) yang dapat dibatalkan.',
            ], 422);
        }

        $permit->update([
            'status' => 'CANCELLED',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permohonan izin berhasil dibatalkan.',
            'data' => $permit,
        ]);
    }
}