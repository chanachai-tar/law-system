<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalCase;
use App\Models\CaseStep;
use App\Models\CaseFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LegalCaseController extends Controller
{
    /**
     * หน้าแดชบอร์ดภาพรวมระบบ
     */
    public function dashboard(Request $request)
    {
        $selectedYear = $request->get('fiscal_year');
        $caseQuery = LegalCase::query();
        $orderQuery = \App\Models\AppointmentOrder::query();

        if ($selectedYear && is_numeric($selectedYear)) {
            $adYear = $selectedYear > 2400 ? $selectedYear - 543 : (int)$selectedYear;
            $caseQuery->whereYear('created_at', $adYear);
            $orderQuery->whereYear('order_date', $adYear);
        }

        // Data Pre-aggregation & Delta Processing (Hybrid)
        // 1. ดึงข้อมูล Base (สรุปยอดเมื่อคืน/ล่าสุด)
        $summary = \App\Models\DashboardSummary::where('summary_date', today()->format('Y-m-d'))->first();
        
        // ถ้ายังไม่มี Summary ของวันนี้ (เช่น cron ยังไม่รัน) ให้รันสดุเพื่อสร้าง Base ก่อน
        if (!$summary) {
            \Illuminate\Support\Facades\Artisan::call('dashboard:calculate-summary');
            $summary = \App\Models\DashboardSummary::where('summary_date', today()->format('Y-m-d'))->first();
        }

        $cutoff = $summary->updated_at;

        // 2. หากมีการ Filter ปีงบประมาณ จะไม่ใช้ Hybrid (เพราะเป็น Query เฉพาะกิจ)
        if ($selectedYear && is_numeric($selectedYear)) {
            $allCount = (clone $caseQuery)->count();
            $completedCount = (clone $caseQuery)->where('status', 'completed')->count();
            $pendingCount = (clone $caseQuery)->where(function($q) {
                $q->where('status', '!=', 'completed')->orWhereNull('status');
            })->count();
            $ordersCount = (clone $orderQuery)->count();
        } else {
            // 3. ใช้เทคนิค Delta Processing (Base + ส่วนต่างของวันนี้) เพื่อลดโหลด Database
            // จำนวนรวม = ยอดสรุปตอนตี 3 + ยอดที่เพิ่งสร้างหลังตี 3
            $newCasesCount = (clone $caseQuery)->where('created_at', '>', $cutoff)->count();
            $allCount = $summary->all_count + $newCasesCount;

            $newOrdersCount = (clone $orderQuery)->where('created_at', '>', $cutoff)->count();
            $ordersCount = $summary->orders_count + $newOrdersCount;

            // สำหรับ Status (Completed/Pending) ถ้าจะให้แม่นยำ 100% กรณีมีการเปลี่ยนสถานะไปมา 
            // สามารถหา Delta จาก updated_at หรือจะ Query สดเฉพาะสถานะก็ได้ (ในที่นี้ Query สดเพื่อความแม่นยำ)
            $completedCount = (clone $caseQuery)->where('status', 'completed')->count();
            $pendingCount = (clone $caseQuery)->where(function($q) {
                $q->where('status', '!=', 'completed')->orWhereNull('status');
            })->count();
        }

        // สำนวนใกล้ครบกำหนด / เกินกำหนด (Pending)
        $urgentCount = LegalCase::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->whereDate('due_date', '>=', now()->startOfDay())
            ->count();

        $overdueCount = LegalCase::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->startOfDay())
            ->count();

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
            });

        foreach ($typeNameMap as $typeId => $typeName) {
            if (!$typeCounts->contains('law_type_id', $typeId)) {
                $typeCounts->push((object)[
                    'law_type_id' => $typeId,
                    'law_type'    => $typeName,
                    'total'       => 0
                ]);
            }
        }

        $typeCounts = $typeCounts->sortBy('law_type_id')->values();

        $stats = [
            'all'          => $allCount,
            'pending'      => $pendingCount,
            'completed'    => $completedCount,
            'orders_count' => $ordersCount,
            'urgent_count' => $urgentCount,
            'overdue_count'=> $overdueCount,
            'types'        => $typeCounts,
        ];

        $recentCases = (clone $caseQuery)->with(['steps', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = (clone $orderQuery)->latest()
            ->take(4)
            ->get();

        // รายการสำนวนด่วน/ใกล้ครบกำหนด
        $urgentCases = LegalCase::where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date', 'asc')
            ->take(4)
            ->get();

        // รายการปีงบประมาณสำหรับตัวเลือก Dropdown
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

        return view('law.dashboard', compact('stats', 'recentCases', 'recentOrders', 'urgentCases', 'years', 'selectedYear'));
    }

    /**
     * แสดงรายการสำนวนทั้งหมด (หน้า Index)
     */
    public function index(Request $request)
    {
        $query = LegalCase::query()->with(['steps.files', 'user']);

        if ($request->filled('case_number')) {
            $query->where('case_number', 'LIKE', '%' . $request->case_number . '%');
        }

        // กรองตามประเภทสำนวน (ที่ส่งมาจากเมนู)
        if ($request->filled('law_type')) {
            $query->where('law_type', $request->law_type);
        }

        // กรองเฉพาะสำนวนของฉัน (แฟ้มของเจ้าหน้าที่)
        if ($request->boolean('my_cases')) {
            $query->where('user_id', Auth::id());
        }

        // กรองตามสถานะกำหนดเวลา (due_status: urgent, overdue, normal)
        if ($request->filled('due_filter')) {
            if ($request->due_filter === 'overdue') {
                $query->where('status', '!=', 'completed')
                      ->whereNotNull('due_date')
                      ->whereDate('due_date', '<', now()->startOfDay());
            } elseif ($request->due_filter === 'urgent') {
                $query->where('status', '!=', 'completed')
                      ->whereNotNull('due_date')
                      ->whereDate('due_date', '<=', now()->addDays(7))
                      ->whereDate('due_date', '>=', now()->startOfDay());
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $cases = $query->latest()->paginate(10)->withQueryString();
        return view('law.index', compact('cases'));
    }

    /**
     * ส่งออกข้อมูลสำนวนเป็นไฟล์ Excel (CSV UTF-8 BOM)
     */
    public function export(Request $request)
    {
        $query = LegalCase::query()->with(['steps', 'user'])->latest();

        if ($request->filled('law_type')) {
            $query->where('law_type', $request->law_type);
        }
        if ($request->boolean('my_cases')) {
            $query->where('user_id', Auth::id());
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->get();

        $filename = 'legal_cases_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($cases) {
            $file = fopen('php://output', 'w');
            // เขียน UTF-8 BOM เพื่อให้ Excel รองรับภาษาไทยสมบูรณ์
            fputs($file, "\xEF\xBB\xBF");

            // หัวคอลัมน์
            fputcsv($file, [
                'ลำดับ',
                'เลขที่สำนวน',
                'ประเภทสำนวน',
                'เรื่อง',
                'ถึง/ผู้เกี่ยวข้อง',
                'วันที่เกิดเหตุ',
                'วันครบกำหนดตามระเบียบ',
                'สถานะ',
                'จำนวนขั้นตอนที่บันทึก',
                'สรุปผลการพิจารณา / บทลงโทษ',
                'ผู้บันทึก/รับผิดชอบ',
                'วันที่สร้างสำนวน',
            ]);

            $typeNames = [1 => 'ตรวจสอบข้อเท็จจริง (ตส.)', 2 => 'ความรับผิดทางละเมิด (สล.)', 3 => 'สอบสวนวินัย (สว.)'];

            foreach ($cases as $index => $c) {
                $statusText = $c->status === 'completed' ? 'เสร็จสิ้น' : 'อยู่ระหว่างดำเนินการ';
                $penaltyOrOutcome = $c->outcome_summary ?? ($c->penalty_type ? 'โทษ: ' . $c->penalty_type : '-');

                fputcsv($file, [
                    $index + 1,
                    $c->case_number,
                    $typeNames[$c->law_type] ?? 'ไม่ระบุ',
                    $c->subject,
                    $c->to ?? '-',
                    $c->incident_date ? $c->incident_date->format('Y-m-d') : '-',
                    $c->due_date ? $c->due_date->format('Y-m-d') : '-',
                    $statusText,
                    $c->steps->count(),
                    $penaltyOrOutcome,
                    $c->user?->name ?? 'ไม่ระบุ',
                    $c->created_at ? $c->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * แฟ้มคลังเอกสารกลุ่มกฎหมายและเอกสารแนบ PDF ทั้งหมด
     */
    public function files(Request $request)
    {
        $query = CaseFile::with(['step.legalCase', 'step.user'])->latest();

        // กรองตามกลุ่มที่เกี่ยวกับกฎหมาย (1=ตส., 2=สล., 3=สว.)
        if ($request->filled('law_type')) {
            $query->whereHas('step.legalCase', function ($q) use ($request) {
                $q->where('law_type', $request->law_type);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('step.legalCase', function ($caseQuery) use ($search) {
                      $caseQuery->where('case_number', 'LIKE', "%{$search}%")
                                ->orWhere('subject', 'LIKE', "%{$search}%");
                  });
            });
        }

        $files = $query->paginate(15)->withQueryString();

        // สถิติแยกตามกลุ่มงานกฎหมาย (Hybrid Aggregation)
        $summary = \App\Models\DashboardSummary::where('summary_date', today()->format('Y-m-d'))->first();
        if (!$summary) {
            \Illuminate\Support\Facades\Artisan::call('dashboard:calculate-summary');
            $summary = \App\Models\DashboardSummary::where('summary_date', today()->format('Y-m-d'))->first();
        }
        $cutoff = $summary->updated_at;

        $newFilesQuery = CaseFile::where('created_at', '>', $cutoff);
        $newOrdersCount = \App\Models\AppointmentOrder::where('created_at', '>', $cutoff)->count();

        $stats = [
            'all'    => $summary->all_files_count + (clone $newFilesQuery)->count(),
            'ts'     => $summary->ts_files_count + (clone $newFilesQuery)->whereHas('step.legalCase', fn($q) => $q->where('law_type', 1))->count(),
            'sl'     => $summary->sl_files_count + (clone $newFilesQuery)->whereHas('step.legalCase', fn($q) => $q->where('law_type', 2))->count(),
            'sw'     => $summary->sw_files_count + (clone $newFilesQuery)->whereHas('step.legalCase', fn($q) => $q->where('law_type', 3))->count(),
            'orders' => $summary->orders_count + $newOrdersCount,
        ];

        return view('law.files', compact('files', 'stats'));
    }

    /**
     * แสดงหน้าฟอร์มสำหรับเพิ่มสำนวนใหม่
     */
    public function create(Request $request)
    {
        $lawType = $request->get('law_type', 1); // ถ้าไม่ส่งมา ให้ default เป็น 1 (ตส.)
        $thaiYear = date('Y') + 543;

        // หาเลขล่าสุดของประเภทนี้เพื่อแสดงตัวอย่างในฟอร์ม (Preview Only)
        $lastCase = LegalCase::where('law_type', $lawType)
            ->whereYear('created_at', date('Y'))
            ->orderBy('running_no', 'desc')
            ->first();

        $nextNumber = $lastCase ? $lastCase->running_no + 1 : 1;

        $group = 'สธ0427.1.1/';
        $prefixes = [1 => 'ตส', 2 => 'สล', 3 => 'สว'];
        $prefix = $prefixes[$lawType] ?? 'คด.';
        $autoCaseNumber = $group . ' ' . $prefix . ' ' . str_pad($nextNumber, 0, '0', STR_PAD_LEFT);

        return view('law.create', compact('autoCaseNumber', 'lawType'));
    }

    /**
     * บันทึกการสร้างสำนวนใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'law_type'      => 'required',
            'subject'       => 'required',
            'to'            => 'nullable|string|max:255',
            'description'   => 'nullable',
            'incident_date' => 'required|date',
            'due_date'      => 'nullable|date',
        ]);

        $lawType = $request->law_type;
        $currentYear = date('Y') + 543;

        // ใช้ Transaction เพื่อป้องกันการออกเลขซ้ำกรณีบันทึกพร้อมกัน
        $newCase = DB::transaction(function () use ($lawType, $currentYear, $request) {

            // 1. หาเลขรันล่าสุดโดยการ Lock แถวไว้
            $lastCase = LegalCase::where('law_type', $lawType)
                ->whereYear('created_at', date('Y'))
                ->lockForUpdate()
                ->orderBy('running_no', 'desc')
                ->first();

            $nextNumber = $lastCase ? $lastCase->running_no + 1 : 1;

            // 2. กำหนดตัวย่อ
            $group = 'สธ0427.1.1/';
            $prefixes = [1 => 'ตส', 2 => 'สล', 3 => 'สว'];
            $prefix = $prefixes[$lawType] ?? 'คด.';
            $fullNumber = $group . ' ' . $prefix . ' ' . str_pad($nextNumber, 0, '0', STR_PAD_LEFT);

            // 3. บันทึกข้อมูล
            return LegalCase::create([
                'law_type'      => $lawType,
                'running_no'    => $nextNumber,
                'case_number'   => $fullNumber,
                'subject'       => $request->subject,
                'to'            => $request->to,
                'description'   => $request->description,
                'incident_date' => $request->incident_date,
                'due_date'      => $request->has('no_due_date') ? null : $request->due_date,
                'user_id'       => Auth::id(),
                'status'        => 'pending'
            ]);
        });

        try {
            broadcast(new \App\Events\CaseCreated($newCase));
        } catch (\Throwable $e) {
            \Log::warning('Broadcast CaseCreated failed: ' . $e->getMessage());
        }

        try {
            \App\Services\TelegramService::notifyCaseCreated($newCase);
        } catch (\Throwable $e) {
            \Log::warning('Telegram notifyCaseCreated failed: ' . $e->getMessage());
        }

        return redirect()->route('cases.index', ['law_type' => $lawType])
            ->with('success', 'ออกเลขรับที่ ' . $newCase->case_number . ' สำเร็จ');
    }

    public function pending(Request $request)
    {
        // แสดงเฉพาะที่ยังไม่เสร็จสิ้น
        $query = LegalCase::where('status', '!=', 'completed')->with(['steps.files', 'user']);
        $cases = $query->latest()->paginate(10);
        return view('law.index', compact('cases'))->with('title', 'แฟ้มสำนวนที่อยู่ระหว่างดำเนินการ');
    }

    public function completed(Request $request)
    {
        // แสดงเฉพาะที่เสร็จสิ้นแล้ว
        $query = LegalCase::where('status', 'completed')->with(['steps.files', 'user']);
        $cases = $query->latest()->paginate(10);
        return view('law.index', compact('cases'))->with('title', 'แฟ้มสำนวนที่แล้วเสร็จ');
    }

    public function closeCase(Request $request, $id)
    {
        $case = LegalCase::findOrFail($id);
        $case->status = 'completed'; // กำหนดค่าสถานะ

        if ($request->filled('outcome_summary')) {
            $case->outcome_summary = $request->outcome_summary;
        }
        if ($request->filled('penalty_type')) {
            $case->penalty_type = $request->penalty_type;
        }
        if ($request->filled('damage_amount')) {
            $case->damage_amount = $request->damage_amount;
        }

        $case->save();

        try {
            broadcast(new \App\Events\CaseClosed($case));
        } catch (\Throwable $e) {
            \Log::warning('Broadcast CaseClosed failed: ' . $e->getMessage());
        }

        try {
            \App\Services\TelegramService::notifyCaseClosed($case);
        } catch (\Throwable $e) {
            \Log::warning('Telegram notifyCaseClosed failed: ' . $e->getMessage());
        }

        return back()->with('success', 'ปิดสำนวนและบันทึกผลการพิจารณาเรียบร้อยแล้ว');
    }
    /**
     * แสดงหน้าสำหรับดำเนินการสำนวนเดิมต่อ (แก้ไข/เพิ่มขั้นตอน)
     */
    public function edit($id)
    {
        $case = LegalCase::with(['steps.files'])->findOrFail($id);
        return view('law.edit', compact('case'));
    }

    /**
     * บันทึกการเพิ่มขั้นตอนใหม่ หรือแก้ไขหัวเรื่องในสำนวนเดิม
     */
    public function update(Request $request, $id)
    {
        $case = LegalCase::findOrFail($id);

        // อัปเดตข้อมูลสำนวน (หัวเรื่อง, ถึง, วันครบกำหนด)
        $caseUpdateData = ['subject' => $request->subject];
        if ($request->has('to')) {
            $caseUpdateData['to'] = $request->to;
        }
        if ($request->has('no_due_date') || empty($request->due_date)) {
            $caseUpdateData['due_date'] = null;
        } else {
            $caseUpdateData['due_date'] = $request->due_date;
        }
        $case->update($caseUpdateData);

        // เพิ่มขั้นตอนใหม่ (รันเลขต่อจากเดิมอัตโนมัติจากฟอร์ม)
        $step = $case->steps()->create([
            'step_num'    => $request->new_step_num,
            'description' => $request->description,
            'user_id'     => Auth::id(), // บันทึก ID คนที่ Login อยู่
        ]);

        try {
            \App\Services\TelegramService::notifyStepAdded($case, $step);
        } catch (\Throwable $e) {
            \Log::warning('Telegram notifyStepAdded failed: ' . $e->getMessage());
        }

        // บันทึกไฟล์ใหม่สำหรับขั้นตอนนี้
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('cases/' . $case->id . '/step_' . $step->step_num, 'public');
                $step->files()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        try {
            broadcast(new \App\Events\CaseStepAdded($case, $step));
        } catch (\Throwable $e) {
            \Log::warning('Broadcast CaseStepAdded failed: ' . $e->getMessage());
        }

        return redirect()->route('cases.index')->with('success', 'บันทึกข้อมูลการดำเนินการ ครั้งที่ ' . $request->new_step_num . ' เรียบร้อย');
    }

    /**
     * ลบสำนวนและไฟล์ทั้งหมดที่เกี่ยวข้อง
     */
    public function destroy($id)
    {
        $case = LegalCase::findOrFail($id);

        // ลบโฟลเดอร์ไฟล์ของสำนวนนี้ใน Storage
        Storage::disk('public')->deleteDirectory('cases/' . $case->id);

        // ลบข้อมูลใน DB (ขั้นตอนและไฟล์ใน DB จะถูกลบด้วย Cascade Delete)
        $case->delete();

        return redirect()->route('cases.index')->with('success', 'ลบสำนวนและเอกสารที่เกี่ยวข้องทั้งหมดเรียบร้อยแล้ว');
    }
}
