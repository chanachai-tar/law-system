<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    use HasFactory;

    // กำหนดชื่อ table ให้ตรงกับ migration (ถ้าใช้ชื่อว่า cases)
    protected $table = 'cases';

    protected $fillable = [
        'law_type',
        'running_no',
        'case_number',
        'subject',
        'to',
        'description',
        'incident_date',
        'user_id', // เพิ่มตรงนี้เพื่อให้บันทึก ID คนสร้างได้
    ];

    // ฟังก์ชันช่วยแสดงชื่อประเภทกฎหมายจาก ID
    public function getLawTypeNameAttribute()
    {
        $types = [
            1 => 'ตรวจสอบข้อเท็จจริง (ตส.)',
            2 => 'ความรับผิดทางละเมิด (สล.)',
            3 => 'สอบสวนวินัย (สว.)',
        ];
        return $types[$this->law_type] ?? 'ไม่ระบุ';
    }

    public function steps()
    {
        return $this->hasMany(CaseStep::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
