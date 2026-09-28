<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as PermitRequest;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // Pantau siswa berizin di rombel yang sedang diampu hari ini
    public function monitoringKelas(Request $request)
    {
        // 1. Sinkronkan secara real-time status OVERDUE
        PermitRequest::syncOverdueStatuses();

        $teacher = $request->user();

        $dayMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        $currentDay = $dayMap[Carbon::now()->dayOfWeekIso] ?? 'Senin';

        // Cari rombel yang diajar guru pada hari ini
        $activeClasses = Schedule::where('teacher_id', $teacher->user_id)
            ->where('day', $currentDay)
            ->pluck('class_name')
            ->unique();

        if ($activeClasses->isEmpty()) {
            $activeClasses = Schedule::where('teacher_id', $teacher->user_id)
                ->pluck('class_name')
                ->unique();
        }

        if ($activeClasses->isEmpty()) {
            $activeClasses = collect(['XII RPL 1']);
        }

        // Ambil user_id seluruh siswa di rombel tersebut
        $studentIds = User::whereIn('class_name', $activeClasses->toArray())
            ->pluck('user_id');

        // Siswa yang berizin aktif, terlambat, atau sudah izin pulang
        $permits = PermitRequest::with(['student:user_id,name,username,class_name,email'])
            ->whereIn('student_id', $studentIds)
            ->whereIn('status', ['PENDING', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'CLOSED'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'day' => $currentDay,
                'classes' => $activeClasses->values(),
                'active_permits' => $permits,
            ],
        ]);
    }

    // Rekapitulasi riwayat perizinan guru pengampu
    public function recapitulasi(Request $request)
    {
        PermitRequest::syncOverdueStatuses();

        $recap = PermitRequest::with(['student:user_id,name,username,class_name,email'])
            ->where('initial_teacher_id', $request->user()->user_id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $recap,
        ]);
    }
}