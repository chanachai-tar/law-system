<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DashboardSummary;
use App\Models\LegalCase;
use App\Models\AppointmentOrder;
use Illuminate\Support\Facades\DB;

class CalculateDashboardSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:calculate-summary {--date= : The date to summarize (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and store the dashboard summary up to the current time (usually run at 03:00 AM)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateStr = $this->option('date') ?? today()->format('Y-m-d');
        $this->info("Calculating dashboard summary for date: {$dateStr}");

        // คำนวณข้อมูลทั้งหมดที่มีอยู่จนถึงวินาทีที่รัน Command นี้
        $caseQuery = LegalCase::query();
        $orderQuery = AppointmentOrder::query();

        $allCount = (clone $caseQuery)->count();
        $completedCount = (clone $caseQuery)->where('status', 'completed')->count();
        $pendingCount = (clone $caseQuery)->where(function($q) {
            $q->where('status', '!=', 'completed')->orWhereNull('status');
        })->count();
        $ordersCount = (clone $orderQuery)->count();

        $urgentCount = (clone $caseQuery)->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->whereDate('due_date', '>=', now()->startOfDay())
            ->count();

        $overdueCount = (clone $caseQuery)->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->startOfDay())
            ->count();

        // คำนวณตามประเภท
        $typeNameMap = [
            1 => 'ตรวจสอบข้อเท็จจริง (ตส.)',
            2 => 'ความรับผิดทางละเมิด (สล.)',
            3 => 'สอบสวนวินัย (สว.)'
        ];

        $typeCounts = (clone $caseQuery)->select('law_type', DB::raw('count(*) as total'))
            ->groupBy('law_type')
            ->get()
            ->map(function ($item) use ($typeNameMap) {
                return (object)[
                    'law_type_id' => $item->law_type,
                    'law_type'    => $typeNameMap[$item->law_type] ?? ('ประเภท ' . $item->law_type),
                    'total'       => $item->total
                ];
            })->keyBy('law_type_id')->toArray();

        foreach ($typeNameMap as $typeId => $typeName) {
            if (!isset($typeCounts[$typeId])) {
                $typeCounts[$typeId] = (object)[
                    'law_type_id' => $typeId,
                    'law_type'    => $typeName,
                    'total'       => 0
                ];
            }
        }
        
        $typeCounts = collect($typeCounts)->sortBy('law_type_id')->values()->toArray();

        // คำนวณไฟล์เอกสาร
        $allFilesCount = \App\Models\CaseFile::count();
        $tsFilesCount = \App\Models\CaseFile::whereHas('step.legalCase', fn($q) => $q->where('law_type', 1))->count();
        $slFilesCount = \App\Models\CaseFile::whereHas('step.legalCase', fn($q) => $q->where('law_type', 2))->count();
        $swFilesCount = \App\Models\CaseFile::whereHas('step.legalCase', fn($q) => $q->where('law_type', 3))->count();

        // บันทึกหรืออัปเดตลงตาราง
        $summary = DashboardSummary::updateOrCreate(
            ['summary_date' => $dateStr],
            [
                'all_count' => $allCount,
                'pending_count' => $pendingCount,
                'completed_count' => $completedCount,
                'orders_count' => $ordersCount,
                'urgent_count' => $urgentCount,
                'overdue_count' => $overdueCount,
                'type_counts' => $typeCounts,
                'all_files_count' => $allFilesCount,
                'ts_files_count' => $tsFilesCount,
                'sl_files_count' => $slFilesCount,
                'sw_files_count' => $swFilesCount,
            ]
        );

        $this->info("Dashboard summary updated successfully. Cutoff time: {$summary->updated_at}");
    }
}
