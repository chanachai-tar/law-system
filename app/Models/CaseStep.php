<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStep extends Model
{
    // เพิ่มบรรทัดนี้ลงไปครับ
    protected $fillable = ['legal_case_id', 'step_num', 'description', 'user_id'];

    public function files()
    {
        return $this->hasMany(CaseFile::class);
    }

    public function legalCase()
    {
        return $this->belongsTo(LegalCase::class, 'legal_case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
