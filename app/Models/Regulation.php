<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    protected $table = 'regulations';

    protected $fillable = [
        'title',
        'category',
        'file_path',
        'file_name',
        'file_size',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCategoryNameAttribute()
    {
        $categories = [
            'regulation'         => 'ระเบียบ / ข้อบังคับ',
            'cabinet_resolution' => 'มติคณะรัฐมนตรี (ครม.)',
            'circular_letter'    => 'หนังสือเวียน / แนวทางปฏิบัติ',
            'general_law'        => 'พระราชบัญญัติ / กฎหมายทั่วไป',
        ];

        return $categories[$this->category] ?? 'เอกสารกฎหมายทั่วไป';
    }
}
