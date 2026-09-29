<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Request as PermitRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Siswa mengirim aduan / keluhan baru
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required', 'in:BULLYING,ACADEMIC,PERSONAL,OTHERS'],
            'follow_up_preference' => ['nullable', 'in:WEB_MESSAGE,WHATSAPP,NEUTRAL_MEET,INFO_ONLY'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'request_id' => ['nullable', 'exists:requests,request_id'],
        ]);

        $student = $request->user();

        // Validasi IDOR: Pastikan request_id yang dilampirkan benar-benar milik siswa yang login
        if ($request->filled('request_id')) {
            $isOwner = PermitRequest::where('request_id', $request->request_id)
                ->where('student_id', $student->user_id)
                ->exists();

            if (!$isOwner) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID surat izin yang dilampirkan tidak valid atau bukan milik Anda.',
                ], 422);
            }
        }

        $title = $request->input('title');
        if (!$title) {
            $categoryNames = [
                'BULLYING' => 'Laporan Perundungan / Bullying',
                'ACADEMIC' => 'Konseling Pembelajaran / Akademik',
                'PERSONAL' => 'Konseling Personal / Pribadi',
                'OTHERS' => 'Laporan / Aduan Umum'
            ];
            $title = $categoryNames[$request->category] ?? 'Laporan Care BK';
        }

        $report = Report::create([
            'student_id' => $student->user_id,
            'request_id' => $request->request_id,
            'category' => $request->category,
            'follow_up_preference' => $request->input('follow_up_preference', 'WEB_MESSAGE'),
            'title' => $title,
            'description' => $request->description,
            'status' => 'OPEN',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan Care Report berhasil dikirim secara aman ke BK.',
            'data' => $report->load(['permit', 'counselor:user_id,name']),
        ], 201);
    }

    // Siswa melihat seluruh laporan miliknya di menu My Tracking
    public function myReports(Request $request)
    {
        $reports = Report::with([
            'permit:request_id,type,status,reason,duration_minutes,alpha_at',
            'counselor:user_id,name,username,email'
        ])
            ->where('student_id', $request->user()->user_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reports,
        ]);
    }
}