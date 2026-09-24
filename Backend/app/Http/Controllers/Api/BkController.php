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
        return response()->json([
            'status' => 'success',
            'data' => [
                'reports_open' => Report::where('status', 'OPEN')->count(),
                'reports_in_progress' => Report::where('status', 'IN_PROGRESS')->count(),
                'reports_resolved' => Report::where('status', 'RESOLVED')->count(),
                'permits_active_today' => PermitRequest::whereIn('status', ['ACTIVE', 'OVERDUE'])
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
                'permits_overdue_today' => PermitRequest::where('status', 'OVERDUE')
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
            ],
        ]);
    }

    // Papan Kanban Care Report
    public function getKanbanReports()
    {
        $reports = Report::with('student:user_id,name,username,class_name')
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

        return response()->json([
            'status' => 'success',
            'message' => 'Status laporan berhasil diperbarui.',
            'data' => $report,
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
        $permits = PermitRequest::with([
            'student:user_id,name,username,class_name',
            'teacher:user_id,name'
        ])
            ->whereIn('status', ['ACTIVE', 'OVERDUE'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $permits,
        ]);
    }
}