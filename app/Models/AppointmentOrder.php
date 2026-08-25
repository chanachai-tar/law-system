<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentOrder extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลแบบ Mass Assignment
    protected $fillable = [
        'order_number',
        'order_date',
        'subject',
        'to',
        'description',
        'owner',
        'status',
        'file_path'
    ];

    // ตัวเลือกเพิ่มเติม: แปลงรูปแบบวันที่เมื่อดึงข้อมูลออกมา
    protected $casts = [
        'order_date' => 'date',
    ];
}
