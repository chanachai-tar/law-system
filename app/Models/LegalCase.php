<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalCase extends Model
{
    use HasFactory;

    protected $table = 'legal_cases';

    protected $fillable = [
        'law_type',
        'running_no',
        'case_number',
        'subject',
        'to',
        'description',
        'incident_date',
        'due_date',
        'status',
        'outcome_summary',
        'penalty_type',
        'damage_amount',
        'user_id'
    ];

    protected $casts = [
        'due_date' => 'date',
        'incident_date' => 'date',
        'damage_amount' => 'decimal:2',
    ];

    public function getDaysRemainingAttribute()
    {
        if (!$this->due_date) return null;
        return (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($this->due_date)->startOfDay(), false);
    }

    public function getDueStatusAttribute()
    {
        if ($this->status === 'completed') {
            return 'completed';
        }
        if (!$this->due_date) {
            return 'no_due_date';
        }
        $days = $this->days_remaining;
        if ($days < 0) {
            return 'overdue';
        } elseif ($days <= 7) {
            return 'due_soon';
        }
        return 'on_track';
    }

    // 1. เพิ่มความสัมพันธ์ไปยังขั้นตอน (CaseStep)

    // 2. เพิ่มความสัมพันธ์ไปยังผู้ใช้ (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 3. (ตัวเลือกเสริม) ฟังก์ชันช่วยแสดงชื่อประเภท
    public function getLawTypeNameAttribute()
    {
        $types = [1 => 'ตส.', 2 => 'สล.', 3 => 'สว.'];
        return $types[$this->law_type] ?? 'ไม่ระบุ';
    }

    public function steps()
    {
        // เปลี่ยนจาก 'case_id' เป็น 'legal_case_id' ให้ตรงกับใน DB
        return $this->hasMany(CaseStep::class, 'legal_case_id');
    }
}
