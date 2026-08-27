<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_date',
        'all_count',
        'pending_count',
        'completed_count',
        'orders_count',
        'urgent_count',
        'overdue_count',
        'type_counts',
        'all_files_count',
        'ts_files_count',
        'sl_files_count',
        'sw_files_count',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'type_counts' => 'array',
    ];
}
