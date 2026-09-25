<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Siswa mengirim aduan / keluhan baru
    public function store(Request $request)
    {
        $request->validate([
            'category' => ['required', 'in:BULLYING,ACADEMIC,PERSONAL,OTHERS'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $student = $request->user();

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
            'category' => $request->category,
            'title' => $title,
            'description' => $request->description,
            'status' => 'OPEN',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan Care Report berhasil dikirim secara aman ke BK.',
            'data' => $report,
        ], 201);
    }

    // Siswa melihat seluruh laporan miliknya di menu My Tracking
    public function myReports(Request $request)
    {
        $reports = Report::where('student_id', $request->user()->user_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reports,
        ]);
    }
}