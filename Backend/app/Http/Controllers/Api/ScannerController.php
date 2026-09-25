<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request as PermitRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Validasi token QR hasil pemindaian kamera satpam di gerbang
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        // Cari perizinan siswa berdasarkan qr_token
        $permit = PermitRequest::with(['student:user_id,name,username,class_name,email'])
            ->where('qr_token', $request->qr_token)
            ->first();

        if (!$permit) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau tidak terdaftar di sistem.',
            ], 404);
        }

        // Cek jika izin sudah selesai atau kedaluwarsa
        if (in_array($permit->status, ['COMPLETED', 'ALPHA', 'CLOSED'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Surat izin ini sudah tidak berlaku (sudah selesai/ditutup).',
                'data' => $permit,
            ], 400);
        }

        // Update status sesuai SOP Gerbang:
        if ($permit->type === 'EXIT_SCHOOL') {
            // Izin Pulang: Langsung ditutup saat melewati gerbang
            $permit->update(['status' => 'CLOSED']);
        } else {
            // Izin Sementara (TEMP): WAKTU DIHITUNG SEJAK BERHASIL DI-SCAN SATPAM DI GERBANG
            $duration = $permit->duration_minutes ?? 30;
            $expiryTime = Carbon::now()->addMinutes($duration);
            $permit->update([
                'status' => 'ACTIVE',
                'expiry_time' => $expiryTime,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Validasi berhasil! Siswa diizinkan melintasi gerbang.',
            'data' => [
                'request_id' => $permit->request_id,
                'type' => $permit->type,
                'status' => $permit->status,
                'duration_minutes' => $permit->duration_minutes,
                'expiry_time' => $permit->expiry_time ? Carbon::parse($permit->expiry_time)->toIso8601String() : null,
                'student' => [
                    'nis' => $permit->student->username,
                    'name' => $permit->student->name,
                    'class_name' => $permit->student->class_name,
                    'email' => $permit->student->email,
                ],
            ],
        ]);
    }
}