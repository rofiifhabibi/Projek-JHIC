<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PermitController;
use App\Http\Controllers\Api\ScannerController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\BkController;

// 1. Endpoint Publik
Route::post('/login', [AuthController::class, 'login']);

// 2. Endpoint Terproteksi Token (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Pilar 2: Portal Siswa (E-Permit, Care Report, Care Tracking)
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('/teachers', [PermitController::class, 'getTeachers']);
        Route::post('/permits', [PermitController::class, 'store']);
        Route::get('/permits/active', [PermitController::class, 'myActivePermit']);
        Route::post('/reports', [ReportController::class, 'store']);
        Route::get('/reports/my', [ReportController::class, 'myReports']);
    });

    // Pilar 3: Dasbor Guru (Dynamic Authority & Validasi Izin)
    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::get('/monitoring', [TeacherController::class, 'monitoringKelas']);
        Route::get('/permits/pending', [PermitController::class, 'pendingRequests']);
        Route::patch('/permits/{id}/approve', [PermitController::class, 'approve']);
        Route::patch('/permits/{id}/resolve', [PermitController::class, 'resolve']);
        Route::get('/recap', [TeacherController::class, 'recapitulasi']);
    });

    // Pilar 4: Portal Satpam Gerbang (Web Scanner)
    Route::middleware('role:satpam')->prefix('satpam')->group(function () {
        Route::post('/scan', [ScannerController::class, 'scan']);
    });

    // Pilar 5: Pusat Data BK (Metrik, Kanban, Investigasi, Global Monitor)
    Route::middleware('role:bk')->prefix('bk')->group(function () {
        Route::get('/metrics', [BkController::class, 'metrics']);
        Route::get('/kanban', [BkController::class, 'getKanbanReports']);
        Route::patch('/reports/{id}/status', [BkController::class, 'updateReportStatus']);
        Route::post('/reports/{id}/investigate', [BkController::class, 'addInvestigationNote']);
        Route::get('/global-monitor', [BkController::class, 'globalMobilityMonitor']);
    });
});