<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Request as PermitRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BkController extends Controller
{
    // Statistik metrik dashboard BK
    public function metrics()
    {
        PermitRequest::syncOverdueStatuses();

        return response()->json([
            'status' => 'success',
            'data' => [
                'reports_open' => Report::where('status', 'OPEN')->count(),
                'reports_in_progress' => Report::where('status', 'IN_PROGRESS')->count(),
                'reports_resolved' => Report::where('status', 'RESOLVED')->count(),
                'permits_active_today' => PermitRequest::whereIn('status', ['ACTIVE', 'OVERDUE'])->count(),
                'permits_overdue_today' => PermitRequest::where('status', 'OVERDUE')->count(),
            ],
        ]);
    }

    // Papan Kanban Care Report
    public function getKanbanReports()
    {
        $reports = Report::with([
            'student:user_id,name,username,class_name,email',
            'permit:request_id,type,status,reason,duration_minutes,alpha_at',
            'counselor:user_id,name,username,email'
        ])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'OPEN' => $reports->where('status', 'OPEN')->values(),
                'IN_PROGRESS' => $reports->where('status', 'IN_PROGRESS')->values(),
                'RESOLVED' => $reports->where('status', 'RESOLVED')->values(),
            ],
        ]);
    }

    // Pindah status kartu Kanban (Open -> In Progress -> Resolved)
    public function updateReportStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:OPEN,IN_PROGRESS,RESOLVED'],
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        // Integrasi Otomatis BK & Presensi:
        // Jika Guru BK menyatakan aduan/klarifikasi TUNTAS (RESOLVED):
        if ($request->status === 'RESOLVED') {
            if ($report->request_id) {
                // Kasus A: Laporan terkait dengan perizinan tertentu -> pulihkan HANYA permit tersebut!
                PermitRequest::where('request_id', $report->request_id)
                    ->where('status', 'ALPHA')
                    ->update(['status' => 'COMPLETED']);
            } elseif ($report->student_id && ($report->category === 'OTHERS' || str_contains(strtolower($report->title), 'klarifikasi'))) {
                // Kasus B: Klarifikasi tanpa request_id eksplisit -> pulihkan HANYA permit ALPHA teranyar
                $latestAlpha = PermitRequest::where('student_id', $report->student_id)
                    ->where('status', 'ALPHA')
                    ->latest()
                    ->first();

                if ($latestAlpha) {
                    $latestAlpha->update(['status' => 'COMPLETED']);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Status laporan berhasil diperbarui.',
            'data' => $report->load(['permit', 'counselor:user_id,name']),
        ]);
    }

    // Guru BK mengirimkan pesan tanggapan / solusi ke siswa (Dua Arah)
    public function respondToStudent(Request $request, int $id)
    {
        $request->validate([
            'response_message' => ['required', 'string'],
            'internal_notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:OPEN,IN_PROGRESS,RESOLVED'],
        ]);

        $report = Report::findOrFail($id);

        $updateData = [
            'counselor_response' => $request->response_message,
            'responded_at' => Carbon::now(),
            'counselor_id' => $request->user()->user_id,
        ];

        if ($request->status) {
            $updateData['status'] = $request->status;
        } elseif ($report->status === 'OPEN') {
            $updateData['status'] = 'IN_PROGRESS';
        }

        if ($request->filled('internal_notes')) {
            $updateData['description'] = $report->description . "\n\n[Catatan Internal BK - " . Carbon::now()->format('d/m/Y H:i') . "]:\n" . $request->internal_notes;
        }

        $report->update($updateData);

        // Jika status diubah menjadi RESOLVED, pulihkan izin jika terkait Alpha
        if ($report->status === 'RESOLVED') {
            if ($report->request_id) {
                PermitRequest::where('request_id', $report->request_id)
                    ->where('status', 'ALPHA')
                    ->update(['status' => 'COMPLETED']);
            } elseif ($report->student_id && ($report->category === 'OTHERS' || str_contains(strtolower($report->title), 'klarifikasi'))) {
                $latestAlpha = PermitRequest::where('student_id', $report->student_id)
                    ->where('status', 'ALPHA')
                    ->latest()
                    ->first();

                if ($latestAlpha) {
                    $latestAlpha->update(['status' => 'COMPLETED']);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan tanggapan bimbingan konseling berhasil dikirim ke siswa.',
            'data' => $report->load(['permit', 'counselor:user_id,name']),
        ]);
    }

    // Catatan investigasi konseling tertutup
    public function addInvestigationNote(Request $request, int $id)
    {
        $request->validate([
            'notes' => ['required', 'string'],
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'description' => $report->description . "\n\n[Catatan BK - " . Carbon::now()->format('d/m/Y H:i') . "]:\n" . $request->notes,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan investigasi berhasil disimpan.',
            'data' => $report,
        ]);
    }

    // Global Mobility Monitor (Pengawasan seluruh izin aktif/overdue sekolah - Read Only)
    public function globalMobilityMonitor()
    {
        PermitRequest::syncOverdueStatuses();

        $permits = PermitRequest::with([
            'student:user_id,name,username,class_name,email',
            'teacher:user_id,name,email'
        ])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $permits,
        ]);
    }
}