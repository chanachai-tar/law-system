<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    protected $fillable = ['case_step_id', 'file_path', 'file_name'];

    public function step()
    {
        return $this->belongsTo(CaseStep::class, 'case_step_id');
    }
}
