<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;

class Request extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'requests';

    // Primary key custom sesuai migration
    protected $primaryKey = 'request_id';

    // Kolom yang dapat diisi
    protected $fillable = [
        'student_id',
        'initial_teacher_id',
        'type',        // 'TEMP' atau 'EXIT_SCHOOL'
        'status',      // 'PENDING', 'APPROVED', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'ALPHA', 'CLOSED', 'REJECTED', 'CANCELLED'
        'reason',
        'duration_minutes',
        'expiry_time',
        'alpha_at',
        'qr_token',
    ];

    protected $casts = [
        'expiry_time' => 'datetime',
        'alpha_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    /**
     * Otomatis sinkronkan izin aktif yang telah melewati batas waktu (expiry_time) menjadi OVERDUE,
     * serta bersihkan izin gantung dari hari-hari sebelumnya.
     * Dioptimalkan dengan cache lock 5 detik untuk mencegah table write-lock saat stress test
     */
    public static function syncOverdueStatuses(): void
    {
        if (Cache::has('overdue_sync_lock')) {
            return;
        }
        Cache::put('overdue_sync_lock', true, 5);

        // 1. Sinkronkan izin aktif yang melewati batas waktu menjadi OVERDUE
        static::where('status', 'ACTIVE')
            ->whereNotNull('expiry_time')
            ->where('expiry_time', '<', Carbon::now())
            ->update(['status' => 'OVERDUE']);

        // 2. Pembersihan Akhir Hari (Auto-cleanup):
        // Tutup izin PENDING dan APPROVED dari hari sebelumnya yang tidak pernah diproses
        static::whereIn('status', ['PENDING', 'APPROVED'])
            ->whereDate('created_at', '<', Carbon::today())
            ->update(['status' => 'CANCELLED']);

        // 3. Tutup izin ACTIVE dan OVERDUE dari hari sebelumnya yang menggantung agar tidak mengunci akun siswa
        static::whereIn('status', ['ACTIVE', 'OVERDUE'])
            ->whereDate('created_at', '<', Carbon::today())
            ->update(['status' => 'CLOSED']);
    }

    /**
     * Relasi ke laporan aduan / klarifikasi BK terkait izin ini
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'request_id', 'request_id');
    }

    /**
     * Relasi ke siswa yang mengajukan izin
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    /**
     * Relasi ke guru pengampu yang menyetujui izin
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'initial_teacher_id', 'user_id');
    }
}