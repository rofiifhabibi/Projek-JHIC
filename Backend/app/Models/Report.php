<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'student_id',
        'request_id',
        'category',    // 'BULLYING', 'FACILITY', 'ACADEMIC', 'PERSONAL', 'OTHERS'
        'follow_up_preference', // 'WEB_MESSAGE', 'WHATSAPP', 'NEUTRAL_MEET', 'INFO_ONLY'
        'title',
        'description',
        'counselor_response',
        'responded_at',
        'counselor_id',
        'status',      // 'OPEN', 'IN_PROGRESS', 'RESOLVED'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Relasi ke siswa pelapor (bisa bernilai null jika laporannya anonim)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    /**
     * Relasi ke guru BK yang merespons
     */
    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id', 'user_id');
    }

    /**
     * Relasi ke perizinan yang diklarifikasi (jika ada)
     */
    public function permit()
    {
        return $this->belongsTo(Request::class, 'request_id', 'request_id');
    }
}