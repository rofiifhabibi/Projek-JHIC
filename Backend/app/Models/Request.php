<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'status',      // 'PENDING', 'ACTIVE', 'OVERDUE', 'COMPLETED', 'ALPHA', 'CLOSED'
        'expiry_time',
        'qr_token',
    ];

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