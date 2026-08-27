@extends('layouts.app')

@php
    $thaiYear = $selectedYear > 2400 ? $selectedYear : $selectedYear + 543;
    $thaiYearNum = thainum($thaiYear);
    $garudaPath = public_path('images/garuda_logo.png');
    $garudaBase64 = file_exists($garudaPath) ? base64_encode(file_get_contents($garudaPath)) : '';
    $garudaDataUri = $garudaBase64 ? 'data:image/png;base64,' . $garudaBase64 : asset('images/garuda_logo.png');
    
    $qrPath = public_path('images/telegram_qr.png');
    $qrBase64 = file_exists($qrPath) ? base64_encode(file_get_contents($qrPath)) : '';
    $qrDataUri = $qrBase64 ? 'data:image/png;base64,' . $qrBase64 : asset('images/telegram_qr.png');
@endphp

@section('header_title')
    <i class="ri-bar-chart-box-line text-indigo-600 text-lg" aria-hidden="true"></i>
    <span>รายงานสรุปภาพรวม ปีงบประมาณ พ.ศ. {{ $thaiYear }}</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">
                รายงานสถิติสำนวนคดีและข้อกฎหมาย
            </h2>
            <p class="text-sm text-slate-500 mt-1">สรุปข้อมูลสถิติการดำเนินงานและสถานะสำนวนทั้งหมดในปีงบประมาณที่เลือก</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center m-0">
                <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-2xl border border-slate-200/80 pl-3 pr-8 py-2.5 transition-all">
                    <label for="report_fiscal_year" class="text-sm font-bold text-slate-700 mr-2 whitespace-nowrap cursor-pointer">
                        ปีงบประมาณ:
                    </label>
                    <select name="fiscal_year" id="report_fiscal_year" onchange="this.form.submit()"
                        class="bg-transparent border-0 p-0 text-sm font-extrabold text-indigo-700 outline-none focus:ring-0 cursor-pointer appearance-none">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" class="bg-white text-slate-800" {{ ($selectedYear == $yr || $selectedYear == ($yr - 543)) ? 'selected' : '' }}>
                                พ.ศ. {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none" aria-hidden="true"></i>
                </div>
            </form>
            
            <button type="button" onclick="exportToWord()" class="px-4 py-2.5 bg-gradient-to-r from-sky-600 to-blue-700 hover:from-sky-700 hover:to-blue-800 text-white rounded-2xl text-sm font-bold shadow-sm transition active:scale-95 flex items-center gap-2">
                <i class="ri-file-word-2-line"></i> โหลด Word (.doc)
            </button>

            <button type="button" onclick="window.print()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl text-sm font-bold shadow-sm transition active:scale-95 flex items-center gap-2">
                <i class="ri-printer-line"></i> สั่งพิมพ์
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="ri-folder-2-line text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-600 text-sm">สำนวนทั้งหมด</h3>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">{{ number_format($statusStats['all']) }}</div>
                <p class="text-xs text-slate-500 mt-1">เรื่องที่รับเข้าระบบ</p>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i class="ri-time-line text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-600 text-sm">อยู่ระหว่างดำเนินการ</h3>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">{{ number_format($statusStats['pending']) }}</div>
                <p class="text-xs text-slate-500 mt-1">เรื่องที่กำลังพิจารณา</p>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="ri-checkbox-circle-line text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-600 text-sm">ดำเนินการแล้วเสร็จ</h3>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">{{ number_format($statusStats['completed']) }}</div>
                <p class="text-xs text-slate-500 mt-1">เรื่องที่ปิดสำนวนแล้ว</p>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <i class="ri-money-dollar-circle-line text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-600 text-sm">มูลค่าความเสียหายรวม</h3>
            </div>
            <div>
                <div class="text-3xl font-black text-slate-800">{{ number_format($statusStats['total_damage'] ?? 0, 2) }}</div>
                <p class="text-xs text-slate-500 mt-1">บาท (ถ้ามีการบันทึก)</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- สถิติแยกตามประเภท -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">สถิติแยกตามประเภทสำนวน</h3>
            </div>
            <div class="p-5 flex-1">
                <div class="space-y-4">
                    @foreach($typeStats as $name => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">{{ $name }}</span>
                        <span class="text-sm font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ number_format($count) }}</span>
                    </div>
                    @if(!$loop->last) <div class="h-px bg-slate-100"></div> @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- กราฟ/ตารางรายเดือน (สามารถขยายเป็น Chart.js ได้ในอนาคต) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">ข้อมูลการดำเนินงานรายเดือน (เดือนที่มีการสร้างสำนวน)</h3>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 font-bold border-b border-slate-200">เดือน</th>
                            <th class="px-5 py-3 font-bold border-b border-slate-200 text-center">รับเข้าระบบ (ใหม่)</th>
                            <th class="px-5 py-3 font-bold border-b border-slate-200 text-center text-amber-600">กำลังดำเนินการ</th>
                            <th class="px-5 py-3 font-bold border-b border-slate-200 text-center text-emerald-600">แล้วเสร็จ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $thaiMonths = [
                                1=>'มกราคม', 2=>'กุมภาพันธ์', 3=>'มีนาคม', 4=>'เมษายน', 5=>'พฤษภาคม', 6=>'มิถุนายน',
                                7=>'กรกฎาคม', 8=>'สิงหาคม', 9=>'กันยายน', 10=>'ตุลาคม', 11=>'พฤศจิกายน', 12=>'ธันวาคม'
                            ];
                        @endphp
                        @forelse($monthlyData as $data)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-3 font-bold text-slate-700">{{ $thaiMonths[$data->month] ?? 'เดือน '.$data->month }}</td>
                            <td class="px-5 py-3 text-center font-semibold text-slate-700">{{ $data->processing + $data->completed }}</td>
                            <td class="px-5 py-3 text-center font-bold text-amber-600">{{ $data->processing }}</td>
                            <td class="px-5 py-3 text-center font-bold text-emerald-600">{{ $data->completed }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500 text-sm">ไม่มีข้อมูลในปฏิทินของปีนี้</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ตารางรายละเอียดสำนวน -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="font-bold text-slate-800">รายละเอียดสำนวนคดีในรอบปี</h3>
            <span class="text-xs font-semibold text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-full">ทั้งหมด {{ count($detailedCases) }} รายการ</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-5 py-3 font-bold border-b border-slate-200">เลขที่สำนวน / วันที่รับเรื่อง</th>
                        <th class="px-5 py-3 font-bold border-b border-slate-200">เรื่อง / ผู้ถูกกล่าวหา</th>
                        <th class="px-5 py-3 font-bold border-b border-slate-200">ประเภท</th>
                        <th class="px-5 py-3 font-bold border-b border-slate-200">สถานะล่าสุด</th>
                        <th class="px-5 py-3 font-bold border-b border-slate-200">ผู้รับผิดชอบ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($detailedCases as $case)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800">{{ $case->case_number ?? 'รอดำเนินการ' }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($case->created_at)->addYears(543)->format('d M y') }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-medium text-slate-700 line-clamp-1">{{ $case->subject }}</div>
                            <div class="text-xs text-slate-500 mt-1 line-clamp-1">ถึง: {{ $case->to ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4">
                            @if($case->law_type == 1)
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">ตส.</span>
                            @elseif($case->law_type == 2)
                                <span class="px-2 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-md">สล.</span>
                            @else
                                <span class="px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-md">สว.</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($case->status == 'completed')
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                                    <i class="ri-checkbox-circle-fill"></i> เสร็จสิ้น
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                    <i class="ri-time-fill"></i> อยู่ระหว่างดำเนินการ
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    {{ mb_substr($case->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-600">{{ $case->user->name ?? '-' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="ri-folder-open-line text-4xl mb-2"></i>
                                <p class="text-sm font-medium">ยังไม่มีข้อมูลสำนวนในปีงบประมาณนี้</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #fff !important; }
    .no-print { display: none !important; }
    .shadow-sm { box-shadow: none !important; }
    .border { border-color: #e2e8f0 !important; }
    .bg-slate-50 { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
    .bg-indigo-50 { background-color: #eef2ff !important; -webkit-print-color-adjust: exact; }
    .bg-amber-50 { background-color: #fffbeb !important; -webkit-print-color-adjust: exact; }
    .bg-emerald-50 { background-color: #ecfdf5 !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection


<div style="display:none;">
<div id="memo-document" class="thai-gov-memo bg-white memo-page-preview rounded-3xl shadow-md border border-slate-200 text-black mx-auto max-w-4xl space-y-3 leading-relaxed">

        <!-- 1. ตราครุฑ และ คำว่า "บันทึกข้อความ" (กึ่งกลางหน้ากระดาษ ตามแบบมาตรฐาน) -->
        <table style="width: 100%; border: none !important; margin: 0 0 10px 0; border-collapse: collapse;">
            <tr style="border: none !important;">
                <td style="width: 3.5cm; vertical-align: top; border: none !important; padding: 0;">
                    <img src="{{ $garudaDataUri }}" alt="ตราครุฑ" style="height: 3.0cm; width: auto; display: block;">
                </td>
                <td style="text-align: center; vertical-align: middle; border: none !important; padding-right: 3.5cm;">
                    <h1 style="font-family: 'TH SarabunIT๙', sans-serif; font-size: 29pt; font-weight: bold; line-height: 1; margin: 0; padding: 0;">
                        บันทึกข้อความ
                    </h1>
                </td>
            </tr>
        </table>

        <!-- 2. ข้อมูลส่วนราชการ (ตามแบบ ๒ ท้ายระเบียบสำนักนายกรัฐมนตรีฯ) -->
        <div style="font-size: 16pt; line-height: 1.25; margin-bottom: 8px;">
            <!-- ส่วนราชการ -->
            <div style="display: flex; align-items: baseline; margin-bottom: 2px;">
                <span style="font-weight: bold; white-space: nowrap; margin-right: 8px;">ส่วนราชการ</span>
                <span>กลุ่มงานกฎหมาย สำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี โทร. ๐ ๔๕๓๒ ๒๐๒๐</span>
            </div>

            <!-- ที่ และ วันที่ -->
            <table style="width: 100%; border: none !important; border-collapse: collapse; margin: 2px 0;">
                <tr style="border: none !important;">
                    <td style="width: 50%; border: none !important; padding: 0; vertical-align: baseline;">
                        <span style="font-weight: bold;">ที่</span>&nbsp;&nbsp;สธ ๐๔๒๗.๑.๑/...................................
                    </td>
                    <td style="width: 50%; border: none !important; padding: 0; vertical-align: baseline;">
                        <span style="font-weight: bold;">วันที่</span>&nbsp;&nbsp;{{ thaidate(now(), 'thai_official') }}
                    </td>
                </tr>
            </table>

            <!-- เรื่อง -->
            <div style="display: flex; align-items: baseline; margin-top: 2px; margin-bottom: 2px;">
                <span style="font-weight: bold; white-space: nowrap; margin-right: 8px;">เรื่อง</span>
                <span>รายงานสรุปผลการดำเนินงานสำนวนคดีและข้อกฎหมาย ประจำปีงบประมาณ พ.ศ. {{ $thaiYearNum }}</span>
            </div>

            <!-- เรียน -->
            <div style="display: flex; align-items: baseline; margin-top: 2px;">
                <span style="font-weight: bold; white-space: nowrap; margin-right: 8px;">เรียน</span>
                <span>ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี</span>
            </div>
        </div>

        <!-- 3. เนื้อหาบันทึกข้อความ (ย่อหน้า 2.5 ซม. ตามแบบหนังสือราชการ) -->
        <div style="font-size: 16pt; line-height: 1.25; text-align: justify; padding-top: 4px;">

            <!-- ย่อหน้าที่ ๑ : ความเป็นมา / ต้นเรื่อง -->
            <p style="text-indent: 2.5cm; margin-bottom: 6px;">
                ด้วยกลุ่มงานกฎหมาย สำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี ได้ดำเนินการติดตาม กำกับ เร่งรัด และรวบรวมข้อมูลการดำเนินงานด้านสำนวนคดีและการดำเนินการทางกฎหมาย ประจำปีงบประมาณ พ.ศ. {{ $thaiYearNum }} เพื่อรายงานผลการปฏิบัติงานต่อผู้บริหารตามระเบียบสารบรรณและระเบียบที่เกี่ยวข้อง
            </p>

            <!-- ย่อหน้าที่ ๒ : ข้อเท็จจริงและสถิติข้อมูล -->
            <p style="text-indent: 2.5cm; margin-bottom: 4px;">
                ในการนี้ กลุ่มงานกฎหมาย ขอรายงานสรุปผลการดำเนินงานในรอบปีงบประมาณ พ.ศ. {{ $thaiYearNum }} มีสำนวนรับเข้าสู่ระบบรวมทั้งสิ้น <span style="font-weight: bold;">{{ thainum(number_format($statusStats['all'])) }}</span> เรื่อง โดยมีรายละเอียดสรุปผลการดำเนินงาน ดังนี้
            </p>

            <div style="padding-left: 2.5cm; margin-bottom: 6px;">
                <p style="margin-bottom: 2px;">๑) สำนวนที่ดำเนินการเสร็จสิ้น จำนวน <span style="font-weight: bold;">{{ thainum(number_format($statusStats['completed'])) }}</span> เรื่อง (คิดเป็นร้อยละ {{ thainum($statusStats['all'] > 0 ? round(($statusStats['completed'] / $statusStats['all']) * 100, 1) : 0) }})</p>
                <p style="margin-bottom: 2px;">๒) สำนวนที่อยู่ระหว่างดำเนินการ จำนวน <span style="font-weight: bold;">{{ thainum(number_format($statusStats['pending'])) }}</span> เรื่อง</p>
                <p style="margin-bottom: 2px;">๓) ยอดเงินชดใช้/ความเสียหายทางละเมิดรวม จำนวน <span style="font-weight: bold;">{{ thainum(number_format($statusStats['total_damage'], 2)) }}</span> บาท</p>
            </div>

            <!-- ตารางที่ ๑ สรุปจำแนกประเภทสำนวน -->
            <div class="avoid-break" style="margin-top: 6px; margin-bottom: 8px;">
                <p style="font-weight: bold; font-size: 14pt; margin-bottom: 2px;">ตารางที่ ๑ สรุปจำนวนสำนวนจำแนกตามประเภทกฎหมาย</p>
                <table class="data-table text-center" style="font-size: 14pt;">
                    <thead>
                        <tr style="background-color: #f1f5f9; font-weight: bold;">
                            <th style="text-align: left; padding: 3px 6px;">ประเภทสำนวน</th>
                            <th style="width: 130px; padding: 3px 6px;">จำนวน (เรื่อง)</th>
                            <th style="width: 130px; padding: 3px 6px;">ร้อยละ (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($typeStats as $label => $val)
                            <tr>
                                <td style="text-align: left; padding: 3px 6px;">{{ $label }}</td>
                                <td style="font-weight: bold; padding: 3px 6px;">{{ thainum(number_format($val)) }}</td>
                                <td style="padding: 3px 6px;">{{ thainum(round(($val / ($statusStats['all'] ?: 1)) * 100, 1)) }}%</td>
                            </tr>
                        @endforeach
                        <tr style="font-weight: bold; background-color: #f8fafc;">
                            <td style="text-align: left; padding: 3px 6px;">รวมทั้งสิ้น</td>
                            <td style="padding: 3px 6px;">{{ thainum(number_format($statusStats['all'])) }}</td>
                            <td style="padding: 3px 6px;">๑๐๐.๐%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ตารางที่ ๒ บัญชีรายละเอียดสำนวนคดี -->
            @if($detailedCases->count() > 0)
            <div class="avoid-break" style="margin-top: 8px; margin-bottom: 8px;">
                <p style="font-weight: bold; font-size: 14pt; margin-bottom: 2px;">ตารางที่ ๒ บัญชีรายละเอียดสำนวนคดีในรอบปีงบประมาณ ({{ thainum($detailedCases->count()) }} เรื่อง)</p>
                <table class="data-table text-left" style="font-size: 13pt;">
                    <thead>
                        <tr style="background-color: #f1f5f9; font-weight: bold; text-align: center;">
                            <th style="width: 35px; padding: 3px;">ลำดับ</th>
                            <th style="width: 120px; padding: 3px;">เลขที่สำนวน</th>
                            <th style="padding: 3px;">เรื่อง / ผู้เกี่ยวข้อง</th>
                            <th style="width: 110px; padding: 3px;">ประเภท</th>
                            <th style="width: 80px; padding: 3px;">วันที่รับ</th>
                            <th style="width: 75px; padding: 3px; text-align: center;">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedCases as $idx => $case)
                            <tr>
                                <td style="text-align: center; padding: 3px;">{{ thainum($idx + 1) }}</td>
                                <td style="font-weight: bold; white-space: nowrap; padding: 3px;">{{ thainum($case->case_number) }}</td>
                                <td style="padding: 3px;">{{ $case->subject }}</td>
                                <td style="padding: 3px;">{{ law_type($case->law_type) }}</td>
                                <td style="white-space: nowrap; padding: 3px;">{{ thaidate($case->created_at, 'short') }}</td>
                                <td style="text-align: center; font-weight: bold; padding: 3px;">
                                    {{ $case->status === 'completed' ? 'เสร็จสิ้น' : 'ดำเนินการ' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- ย่อหน้าคำลงท้าย -->
            <div class="avoid-break" style="padding-top: 4px;">
                <p style="text-indent: 2.5cm; margin-bottom: 0;">
                    จึงเรียนมาเพื่อโปรดทราบและพิจารณา
                </p>
            </div>

            <!-- 4. ส่วนลงนาม (จัดวางเยื้องขวา กึ่งกลางตามแบบหนังสือราชการ) -->
            <div class="avoid-break" style="padding-top: 20px; margin-bottom: 14px;">
                <table style="width: 100%; border: none !important; border-collapse: collapse;">
                    <tr style="border: none !important;">
                        <td style="width: 40%; border: none !important; padding: 0;"></td>
                        <!-- ลายมือชื่อนิติกรผู้จัดทำรายงาน -->
                        <td style="width: 60%; border: none !important; padding: 0; text-align: center; vertical-align: top;">
                            <div style="display: inline-block; text-align: center; font-size: 16pt; line-height: 1.25;">
                                <p style="margin-bottom: 2px;">(ลงชื่อ)................................................................</p>
                                <p style="margin-bottom: 2px;">( {{ Auth::user()->name ?? '................................................................' }} )</p>
                                <p style="font-size: 14pt; color: #333; margin-bottom: 0;">นิติกร / ผู้จัดทำรายงาน</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- 5. ส่วนความเห็นชอบและคำสั่งการของผู้บริหาร -->
            <div class="avoid-break" style="border-top: 1px solid #000000; padding-top: 8px; margin-top: 8px;">
                <table style="width: 100%; border: none !important; border-collapse: collapse; font-size: 14pt;">
                    <tr style="border: none !important;">
                        <!-- ความเห็นหัวหน้ากลุ่มงาน -->
                        <td style="width: 50%; border: none !important; padding: 0 10px 0 0; vertical-align: top;">
                            <p style="font-weight: bold; margin-bottom: 2px;">ความเห็นของหัวหน้ากลุ่มงานกฎหมาย:</p>
                            <p style="margin-bottom: 2px;">...........................................................................................</p>
                            <p style="margin-bottom: 4px;">...........................................................................................</p>
                            <div style="text-align: center; padding-top: 4px; font-size: 13pt; line-height: 1.2;">
                                <p style="margin-bottom: 2px;">(ลงชื่อ)................................................................</p>
                                <p style="margin-bottom: 2px;">( ................................................................ )</p>
                                <p style="margin-bottom: 2px;">หัวหน้ากลุ่มงานกฎหมาย</p>
                                <p style="margin-bottom: 0;">วันที่ ...... / ............ / ..........</p>
                            </div>
                        </td>

                        <!-- คำสั่ง / ข้อสั่งการ ผอ.สคร.๑๐ -->
                        <td style="width: 50%; border: none !important; border-left: 1px solid #000000 !important; padding: 0 0 0 12px; vertical-align: top;">
                            <p style="font-weight: bold; margin-bottom: 2px;">คำสั่ง / ข้อสั่งการ ผอ.สคร.๑๐ อุบลราชธานี:</p>
                            <div style="padding-left: 6px; margin-bottom: 4px; font-size: 13pt;">
                                <label style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px; cursor: pointer;">
                                    <input type="checkbox" style="border: 1px solid #000;">
                                    <span>ทราบ</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px; cursor: pointer;">
                                    <input type="checkbox" style="border: 1px solid #000;">
                                    <span>ดำเนินการตามเสนอ</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; margin-bottom: 0; cursor: pointer;">
                                    <input type="checkbox" style="border: 1px solid #000;">
                                    <span>อื่นๆ ................................................................</span>
                                </label>
                            </div>
                            <div style="text-align: center; padding-top: 4px; font-size: 13pt; line-height: 1.2;">
                                <p style="margin-bottom: 2px;">(ลงชื่อ)................................................................</p>
                                <p style="margin-bottom: 2px;">( ................................................................ )</p>
                                <p style="margin-bottom: 2px;">ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ ๑๐</p>
                                <p style="margin-bottom: 0;">วันที่ ...... / ............ / ..........</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

    </div>

</div>
</div>


@push('scripts')
<script>
    /**
     * ส่งออกเอกสารแบบบันทึกข้อความราชการเป็นไฟล์ Microsoft Word (.doc)
     * บังคับใช้ฟอนต์มาตรฐานราชการ TH SarabunIT๙ 16pt (หัวข้อ 29pt)
     * ระยะขอบมาตรฐาน: ซ้าย 3.0cm, ขวา 2.0cm, บน 2.5cm, ล่าง 2.0cm
     */
    function exportToWord() {
        const memoElement = document.getElementById('memo-document');
        if (!memoElement) {
            alert('ไม่พบข้อมูลเอกสารสำหรับส่งออก');
            return;
        }

        const clone = memoElement.cloneNode(true);

        // Pre-header for Microsoft Word formatting with native TH SarabunIT๙ font-face
        const preHtml = `
            <html xmlns:v="urn:schemas-microsoft-com:vml"
                  xmlns:o="urn:schemas-microsoft-com:office:office" 
                  xmlns:w="urn:schemas-microsoft-com:office:word" 
                  xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
                  xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset='utf-8'>
                <title>บันทึกข้อความ รายงานสรุปผลงานนิติกร</title>
                <!--[if gte mso 9]>
                <xml>
                    <w:WordDocument>
                        <w:View>Print</w:View>
                        <w:Zoom>100</w:Zoom>
                        <w:DoNotOptimizeForBrowser/>
                    </w:WordDocument>
                </xml>
                <![endif]-->
                <style>
                    @font-face {
                        font-family: "TH SarabunIT๙";
                        panose-1: 2 11 5 0 4 2 0 2 0 4;
                        mso-font-charset: 222;
                        mso-generic-font-family: swiss;
                        mso-font-pitch: variable;
                        mso-font-signature: 16777219 0 0 0 65536 0;
                    }
                    @font-face {
                        font-family: "TH SarabunIT๙";
                        panose-1: 2 11 5 0 4 2 0 2 0 4;
                        mso-font-charset: 222;
                        mso-generic-font-family: swiss;
                        mso-font-pitch: variable;
                        mso-font-signature: 16777219 0 0 0 65536 0;
                    }

                    @page Section1 {
                        size: 21.0cm 29.7cm;
                        margin: 2.5cm 2.0cm 2.0cm 3.0cm; /* ขอบบน 2.5cm, ขวา 2.0cm, ล่าง 2.0cm, ซ้าย 3.0cm (มาตรฐานสารบรรณ) */
                        mso-header-margin: 36.0pt;
                        mso-footer-margin: 36.0pt;
                        mso-paper-source: 0;
                    }
                    div.Section1 { 
                        page: Section1; 
                    }

                    /* บังคับใช้ฟอนต์ TH SarabunIT๙ ทุกจุด */
                    *, body, div, p, span, table, th, td, h1, h2, h3, a, b, strong, i, em {
                        font-family: "TH SarabunIT๙", sans-serif !important;
                        mso-ascii-font-family: "TH SarabunIT๙" !important;
                        mso-hansi-font-family: "TH SarabunIT๙" !important;
                        mso-bidi-font-family: "TH SarabunIT๙" !important;
                        mso-fareast-font-family: "TH SarabunIT๙" !important;
                    }

                    body {
                        font-size: 16.0pt;
                        line-height: 1.2;
                        color: #000000;
                    }

                    h1 {
                        font-size: 29.0pt !important;
                        font-weight: bold;
                        text-align: center;
                        margin: 0;
                        padding: 0;
                        line-height: 1;
                    }

                    p {
                        font-size: 16.0pt;
                        margin-top: 0;
                        margin-bottom: 4.0pt;
                        line-height: 1.2;
                    }

                    table {
                        border-collapse: collapse;
                        width: 100%;
                        margin-top: 6.0pt;
                        margin-bottom: 6.0pt;
                    }

                    th, td {
                        border: 1.0pt solid #000000;
                        padding: 4.0pt 6.0pt;
                        font-size: 14.0pt;
                        line-height: 1.15;
                    }

                    th {
                        font-weight: bold;
                        background-color: #f1f5f9;
                    }

                    .indent {
                        text-indent: 2.5cm;
                    }

                    .text-right { text-align: right; }
                    .text-center { text-align: center; }
                    .font-bold { font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="Section1">
        `;

        const postHtml = `
                </div>
            </body>
            </html>
        `;

        const fullHtml = preHtml + clone.innerHTML + postHtml;
        const blob = new Blob(['\ufeff' + fullHtml], {
            type: 'application/msword;charset=utf-8'
        });

        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `บันทึกข้อความ_รายงานสรุปผลงานนิติกร_พศ_{{ $thaiYear }}.doc`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
</script>
@endpush
