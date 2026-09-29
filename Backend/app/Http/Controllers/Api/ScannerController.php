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
     * Mendukung alur Scan Keluar (Check-out) dan Scan Kembali (Check-in)
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $rawToken = trim($request->qr_token);
        $cleanId = ltrim($rawToken, '#');

        // Sinkronkan status OVERDUE sebelum pemindaian
        PermitRequest::syncOverdueStatuses();

        return \Illuminate\Support\Facades\DB::transaction(function () use ($rawToken, $cleanId) {
            // Cari perizinan siswa berdasarkan qr_token atau nomor ID surat izin dengan pessimistic lock
            $permit = PermitRequest::with(['student:user_id,name,username,class_name,email'])
                ->where(function ($q) use ($rawToken, $cleanId) {
                    $q->where('qr_token', $rawToken);
                    if (is_numeric($cleanId)) {
                        $q->orWhere('request_id', (int) $cleanId);
                    }
                })
                ->lockForUpdate()
                ->first();

            if (!$permit) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode atau ID surat izin tidak valid / tidak ditemukan di sistem.',
                ], 404);
            }

            // Cek jika izin sudah selesai atau ditutup
            if (in_array($permit->status, ['COMPLETED', 'CLOSED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Surat izin ini sudah selesai/ditutup sebelumnya.',
                    'data' => $this->formatPermitData($permit),
                ], 400);
            }

            // Cek jika izin ditandai ALPHA oleh guru
            if ($permit->status === 'ALPHA') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Izin ini telah ditandai ALPHA oleh Guru Pengampu. Siswa harus menghadap Guru Piket atau BK.',
                    'data' => $this->formatPermitData($permit),
                ], 400);
            }

            // Cek jika izin ditolak atau dibatalkan
            if (in_array($permit->status, ['REJECTED', 'CANCELLED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Surat izin ini telah ditolak atau dibatalkan.',
                    'data' => $this->formatPermitData($permit),
                ], 400);
            }

            // 1. KASUS IZIN PULANG (EXIT_SCHOOL)
            if ($permit->type === 'EXIT_SCHOOL') {
                if ($permit->status === 'PENDING') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Izin pulang ini belum disetujui oleh guru pengampu.',
                        'data' => $this->formatPermitData($permit),
                    ], 400);
                }

                // Izin Pulang: Langsung ditutup saat melewati gerbang keluar
                $permit->update(['status' => 'CLOSED']);

                return response()->json([
                    'status' => 'success',
                    'scan_action' => 'EXIT',
                    'message' => 'Validasi berhasil! Izin Pulang disetujui. Siswa dipersilakan meninggalkan sekolah.',
                    'data' => $this->formatPermitData($permit),
                ]);
            }

            // 2. KASUS IZIN SEMENTARA (TEMP)
            // ALUR A: SCAN KELUAR (Check-out di Gerbang)
            if ($permit->status === 'APPROVED') {
                $duration = $permit->duration_minutes ?? 30;
                $expiryTime = Carbon::now()->addMinutes($duration);

                $permit->update([
                    'status' => 'ACTIVE',
                    'expiry_time' => $expiryTime,
                ]);

                return response()->json([
                    'status' => 'success',
                    'scan_action' => 'CHECK_OUT',
                    'message' => "Validasi keluar berhasil! Siswa diizinkan keluar sekolah. Batas waktu {$duration} menit dimulai.",
                    'data' => $this->formatPermitData($permit),
                ]);
            }

            // ALUR B: SCAN KEMBALI TEPAT WAKTU (Check-in Masuk di Gerbang)
            if ($permit->status === 'ACTIVE') {
                // Anti-double-scan cooldown: Cegah auto-complete instan jika permit baru saja diaktifkan keluar (< 30 detik)
                if ($permit->updated_at && $permit->updated_at->diffInSeconds(Carbon::now()) < 30) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Siswa baru saja melakukan scan keluar kurang dari 30 detik yang lalu. Mohon tunggu sejenak sebelum scan masuk kembali.',
                        'data' => $this->formatPermitData($permit),
                    ], 422);
                }

                $permit->update([
                    'status' => 'COMPLETED',
                ]);

                return response()->json([
                    'status' => 'success',
                    'scan_action' => 'CHECK_IN_ON_TIME',
                    'message' => 'Siswa telah kembali ke sekolah tepat waktu! Izin selesai dan presensi tuntas.',
                    'data' => $this->formatPermitData($permit),
                ]);
            }

            // ALUR C: SCAN KEMBALI TERLAMBAT (Check-in Masuk saat OVERDUE)
            if ($permit->status === 'OVERDUE') {
                $permit->update([
                    'status' => 'COMPLETED',
                ]);

                return response()->json([
                    'status' => 'success',
                    'scan_action' => 'CHECK_IN_LATE',
                    'message' => 'Siswa kembali melintasi gerbang (TERLAMBAT). Izin diselesaikan dengan catatan keterlambatan.',
                    'data' => $this->formatPermitData($permit),
                ]);
            }

            // Jika permit masih PENDING saat di scan di gerbang
            if ($permit->status === 'PENDING') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Izin belum disetujui oleh guru pengampu. Siswa belum diizinkan keluar gerbang.',
                    'data' => $this->formatPermitData($permit),
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Status izin tidak valid untuk pemindaian gerbang.',
                'data' => $this->formatPermitData($permit),
            ], 400);
        });
    }

    /**
     * Format payload data respon
     */
    private function formatPermitData($permit)
    {
        return [
            'request_id' => $permit->request_id,
            'type' => $permit->type,
            'status' => $permit->status,
            'duration_minutes' => $permit->duration_minutes,
            'expiry_time' => $permit->expiry_time ? Carbon::parse($permit->expiry_time)->toIso8601String() : null,
            'student' => [
                'nis' => $permit->student->username ?? '-',
                'name' => $permit->student->name ?? '-',
                'class_name' => $permit->student->class_name ?? '-',
                'email' => $permit->student->email ?? '-',
            ],
        ];
    }
}