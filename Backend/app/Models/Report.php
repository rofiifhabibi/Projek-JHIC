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
        'category',    // 'BULLYING', 'FACILITY', 'ACADEMIC', 'PERSONAL', 'OTHERS'
        'title',
        'description',
        'status',      // 'OPEN', 'IN_PROGRESS', 'RESOLVED'
    ];

    /**
     * Relasi ke siswa pelapor (bisa bernilai null jika laporannya anonim)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}