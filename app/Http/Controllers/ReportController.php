<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\LegalCase;
use App\Models\CaseStep;
use App\Models\CaseFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('fiscal_year', date('Y'));
        $adYear = $selectedYear > 2400 ? $selectedYear - 543 : (int)$selectedYear;

        $caseQuery = LegalCase::whereYear('created_at', $adYear);

        // 1. สถิติตามสถานะ
        $statusStats = [
            'all'        => (clone $caseQuery)->count(),
            'pending'    => (clone $caseQuery)->where(function($q) {
                $q->where('status', '!=', 'completed')->orWhereNull('status');
            })->count(),
            'completed'  => (clone $caseQuery)->where('status', 'completed')->count(),
            'total_damage' => (clone $caseQuery)->sum('damage_amount'),
        ];

        // 2. สถิติแยกตามประเภท
        $typeNameMap = [
            1 => 'ตรวจสอบข้อเท็จจริง (ตส.)',
            2 => 'ความรับผิดทางละเมิด (สล.)',
            3 => 'สอบสวนวินัย (สว.)'
        ];

        $rawTypeStats = (clone $caseQuery)->select('law_type', DB::raw('count(*) as total'))
            ->groupBy('law_type')
            ->pluck('total', 'law_type')
            ->toArray();

        $typeStats = [];
        foreach ($typeNameMap as $id => $name) {
            $typeStats[$name] = $rawTypeStats[$id] ?? 0;
        }

        // 3. สถิติรายเดือนในปีที่เลือก
        $monthlyData = LegalCase::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status != "completed" OR status IS NULL THEN 1 ELSE 0 END) as processing')
        )
            ->whereYear('created_at', $adYear)
            ->groupBy('month')
            ->get();

        // 4. รายการสำนวนสำคัญสำหรับพิมพ์แนบรายงาน
        $detailedCases = (clone $caseQuery)->with(['user', 'steps'])
            ->orderBy('created_at', 'desc')
            ->get();

        // รายการปีงบประมาณ
        $years = LegalCase::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn($y) => $y + 543)
            ->toArray();

        $currentThaiYear = date('Y') + 543;
        if (!in_array($currentThaiYear, $years)) {
            array_unshift($years, $currentThaiYear);
        }

        return view('law.report', compact('statusStats', 'typeStats', 'monthlyData', 'detailedCases', 'years', 'selectedYear', 'adYear'));
    }
}
