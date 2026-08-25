@extends('layouts.app')

@php
    $thaiYear = $selectedYear > 2400 ? $selectedYear : $selectedYear + 543;
    $thaiYearNum = thainum($thaiYear);
    $garudaPath = public_path('images/garuda_logo.png');
    $garudaBase64 = file_exists($garudaPath) ? base64_encode(file_get_contents($garudaPath)) : '';
    $garudaDataUri = $garudaBase64 ? 'data:image/png;base64,' . $garudaBase64 : asset('images/garuda_logo.png');
@endphp

@section('header_title')
    <i class="ri-file-paper-2-line text-indigo-600 text-lg" aria-hidden="true"></i>
    <span>แบบบันทึกข้อความรายงานสรุปผลงานนิติกร</span>
@endsection

@section('content')
<style>
    /* 1. โหลดฟอนต์ราชการ TH Sarabun New จาก Local Server โดยตรง */
    @font-face {
        font-family: 'TH Sarabun New';
        src: url('{{ asset('fonts/THSarabunNew.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'TH Sarabun New';
        src: url('{{ asset('fonts/THSarabunNew-Bold.ttf') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'TH Sarabun PSK';
        src: url('{{ asset('fonts/THSarabunNew.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'TH Sarabun PSK';
        src: url('{{ asset('fonts/THSarabunNew-Bold.ttf') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
        font-display: swap;
    }

    /* 2. บังคับใช้ฟอนต์ TH Sarabun New */
    .thai-gov-memo,
    .thai-gov-memo * {
        font-family: 'TH Sarabun New', 'TH Sarabun PSK', sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .thai-gov-memo {
        font-size: 16pt !important;
        line-height: 1.2 !important;
        color: #000000 !important;
    }

    .thai-gov-memo h1 {
        font-size: 29pt !important;
        font-weight: bold !important;
        line-height: 1 !important;
    }

    .thai-gov-memo p {
        font-size: 16pt !important;
        line-height: 1.2 !important;
        margin-top: 0;
        margin-bottom: 0.25rem;
    }

    .thai-gov-memo table.data-table {
        font-size: 14pt !important;
        border-collapse: collapse !important;
        width: 100% !important;
        margin-top: 0.4rem;
        margin-bottom: 0.4rem;
    }

    .thai-gov-memo table.data-table th,
    .thai-gov-memo table.data-table td {
        font-size: 14pt !important;
        padding: 3px 6px !important;
        border: 1px solid #000000 !important;
        color: #000000 !important;
        line-height: 1.15 !important;
    }

    /* มาตรฐานขอบกระดาษราชการไทย: บน 2.5cm, ล่าง 2.0cm, ซ้าย 3.0cm (เจาะแฟ้ม), ขวา 2.0cm */
    @page {
        size: A4 portrait;
        margin-top: 25mm !important;
        margin-bottom: 20mm !important;
        margin-left: 30mm !important;
        margin-right: 20mm !important;
    }

    @media print {
        *, html, body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        nav, aside, header, footer, #sidebar, .no-print, button, .navbar, .sidebar-wrapper, #socket-status-badge {
            display: none !important;
            visibility: hidden !important;
            width: 0 !important;
            height: 0 !important;
        }
        html, body {
            background-color: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            color: #000000 !important;
            font-family: 'TH Sarabun New', 'TH Sarabun PSK', sans-serif !important;
            font-size: 16pt !important;
            line-height: 1.15 !important;
        }
        .main-content-wrapper, .flex-1 {
            margin-left: 0 !important;
            padding: 0 !important;
            background-color: #ffffff !important;
        }
        #memo-document {
            display: block !important;
            visibility: visible !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important; /* ปิด padding ใน print เพื่อไม่ให้เบิ้ลกับ @page margin */
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .avoid-break {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }

    /* ระยะขอบแสดงผลบนหน้าจอ A4 Preview */
    .memo-page-preview {
        padding-top: 25mm;
        padding-bottom: 20mm;
        padding-left: 30mm;
        padding-right: 20mm;
    }
    @media (max-width: 640px) {
        .memo-page-preview {
            padding: 1.25rem 0.75rem !important;
        }
    }
</style>

<div class="max-w-5xl mx-auto space-y-5">

    <!-- Top Action Toolbar (Screen View Only) -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 no-print bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    แบบบันทึกข้อความราชการ (TH Sarabun New)
                </span>
                <span class="text-xs text-slate-500 font-medium">ปีงบประมาณ พ.ศ. {{ $thaiYear }}</span>
            </div>
            <h2 class="text-base sm:text-lg font-black text-slate-800 tracking-tight">
                รายงานสรุปผลการดำเนินงานสำนวนคดีและข้อกฎหมาย
            </h2>
            <p class="text-xs text-slate-500">
                ฟอนต์ TH Sarabun New ขนาด 16pt (หัวข้อ 29pt) • ขอบซ้าย 3 ซม. • บน 2.5 ซม. • ขวา/ล่าง 2 ซม.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
            <!-- Year Selector Form -->
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center m-0">
                <div class="relative flex items-center bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-2xl border border-slate-200/80 pl-3 pr-7 py-2 transition-all">
                    <label for="report_fiscal_year" class="text-xs font-bold text-slate-700 mr-1.5 whitespace-nowrap cursor-pointer select-none">
                        ปี:
                    </label>
                    <select name="fiscal_year" id="report_fiscal_year" onchange="this.form.submit()" aria-label="เลือกปีรายงาน"
                        style="border-radius: 1rem !important; -webkit-appearance: none !important; appearance: none !important; background-color: transparent !important; border: none !important;"
                        class="bg-transparent border-0 p-0 text-xs font-extrabold text-indigo-700 outline-none focus:ring-0 cursor-pointer appearance-none">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" class="bg-white text-slate-800 font-semibold" {{ ($selectedYear == $yr || $selectedYear == ($yr - 543)) ? 'selected' : '' }}>
                                พ.ศ. {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                </div>
            </form>

            <!-- Export to Word (.doc) Button -->
            <button type="button" onclick="exportToWord()"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-sky-600 to-blue-700 hover:from-sky-700 hover:to-blue-800 text-white rounded-2xl text-xs font-bold shadow-sm shadow-sky-600/20 transition active:scale-95 whitespace-nowrap"
                title="ส่งออกเอกสารแบบบันทึกข้อความเพื่อนำไปแก้ไขต่อใน Microsoft Word">
                <i class="ri-file-word-2-line text-base" aria-hidden="true"></i>
                <span>บันทึกเป็น Word (.doc)</span>
            </button>

            <!-- Print / Save PDF Button -->
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-2xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition active:scale-95 whitespace-nowrap"
                title="สั่งพิมพ์หรือบันทึกเป็นไฟล์ PDF ขนาดกระดาษ A4">
                <i class="ri-file-pdf-2-line text-base" aria-hidden="true"></i>
                <span>พิมพ์ / บันทึกเป็น PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Thai Government Memorandum Document (แบบบันทึกข้อความ) -->
    <div id="memo-document" class="thai-gov-memo bg-white memo-page-preview rounded-3xl shadow-md border border-slate-200 text-black mx-auto max-w-4xl space-y-4 leading-relaxed">

        <!-- 1. Garuda Emblem & "บันทึกข้อความ" Title (จัดวางด้วยตารางมาตรฐานราชการ) -->
        <table style="width: 100%; border: none !important; margin: 0 0 6px 0; border-collapse: collapse;">
            <tr style="border: none !important;">
                <td style="width: 3.5cm; vertical-align: bottom; border: none !important; padding: 0;">
                    <img src="{{ $garudaDataUri }}" alt="ตราครุฑ" style="height: 3.0cm; width: auto; display: block;">
                </td>
                <td style="text-align: center; vertical-align: bottom; border: none !important; padding-right: 3.5cm; padding-bottom: 2px;">
                    <span style="font-family: 'TH Sarabun New', 'TH Sarabun PSK', sans-serif; font-size: 29pt; font-weight: bold; line-height: 1; display: inline-block;">
                        บันทึกข้อความ
                    </span>
                </td>
            </tr>
        </table>

        <!-- 2. Official Metadata Header Fields (มาตรฐานสารบรรณแบบ ๒ ท้ายระเบียบฯ) -->
        <table style="width: 100%; border: none !important; border-top: 2px solid #000000 !important; margin: 0 0 8px 0; border-collapse: collapse; font-size: 16pt;">
            <tr style="border: none !important;">
                <td colspan="2" style="border: none !important; padding: 4px 0 2px 0;">
                    <span style="font-weight: bold;">ส่วนราชการ</span>&nbsp;&nbsp;กลุ่มงานกฎหมาย สำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี โทร. ๐ ๔๕๓๒ ๒๐๒๐
                </td>
            </tr>
            <tr style="border: none !important;">
                <td style="width: 55%; border: none !important; padding: 2px 0;">
                    <span style="font-weight: bold;">ที่</span>&nbsp;&nbsp;สธ ๐๔๒๗.๑.๑/.............................................
                </td>
                <td style="width: 45%; border: none !important; padding: 2px 0;">
                    <span style="font-weight: bold;">วันที่</span>&nbsp;&nbsp;{{ thaidate(now(), 'full') }}
                </td>
            </tr>
            <tr style="border: none !important;">
                <td colspan="2" style="border: none !important; padding: 2px 0;">
                    <span style="font-weight: bold;">เรื่อง</span>&nbsp;&nbsp;รายงานสรุปผลการดำเนินงานสำนวนคดีและข้อกฎหมาย ประจำปีงบประมาณ พ.ศ. {{ $thaiYear }}
                </td>
            </tr>
            <tr style="border: none !important;">
                <td colspan="2" style="border: none !important; border-top: 1px solid #000000 !important; padding: 4px 0 2px 0;">
                    <span style="font-weight: bold;">เรียน</span>&nbsp;&nbsp;ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี
                </td>
            </tr>
        </table>

        <!-- 3. Memo Body Content -->
        <div class="space-y-4 text-black font-normal leading-relaxed text-justify pt-1" style="font-size: 16pt;">

            <!-- ข้อ ๑. ต้นเรื่อง -->
            <div>
                <p style="text-indent: 2.5cm; font-size: 16pt; line-height: 1.25;">
                    <span style="font-weight: bold;">๑. ต้นเรื่อง</span>&nbsp;&nbsp;ด้วยกลุ่มงานกฎหมาย สำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี ได้ดำเนินการติดตาม กำกับ เร่งรัด และรวบรวมข้อมูลการดำเนินงานด้านสำนวนคดีและการดำเนินการทางกฎหมาย ประจำปีงบประมาณ พ.ศ. {{ $thaiYear }} เพื่อรายงานผลการปฏิบัติงานต่อผู้บริหารตามระเบียบสารบรรณและระเบียบที่เกี่ยวข้อง
                </p>
            </div>

            <!-- ข้อ ๒. ข้อมูลสถิติและผลการดำเนินงาน -->
            <div>
                <p style="text-indent: 2.5cm; font-size: 16pt; line-height: 1.25;">
                    <span style="font-weight: bold;">๒. ข้อมูลสถิติและผลการดำเนินงาน</span>&nbsp;&nbsp;ในรอบปีงบประมาณ พ.ศ. {{ $thaiYear }} มีสำนวนรับเข้าสู่ระบบรวมทั้งสิ้น <span style="font-weight: bold;">{{ number_format($statusStats['all']) }}</span> เรื่อง โดยมีรายละเอียดสรุปผลการดำเนินงาน ดังนี้
                </p>
                <div class="space-y-0.5 mt-1" style="padding-left: 2.5cm; font-size: 16pt; line-height: 1.25;">
                    <p>๒.๑ สำนวนที่ดำเนินการเสร็จสิ้น จำนวน <span style="font-weight: bold;">{{ number_format($statusStats['completed']) }}</span> เรื่อง (คิดเป็นร้อยละ {{ $statusStats['all'] > 0 ? round(($statusStats['completed'] / $statusStats['all']) * 100, 1) : 0 }})</p>
                    <p>๒.๒ สำนวนที่อยู่ระหว่างดำเนินการ จำนวน <span style="font-weight: bold;">{{ number_format($statusStats['pending']) }}</span> เรื่อง</p>
                    <p>๒.๓ ยอดเงินชดใช้/ความเสียหายทางละเมิดรวม จำนวน <span style="font-weight: bold;">{{ number_format($statusStats['total_damage'], 2) }}</span> บาท</p>
                </div>

                <!-- ตารางที่ ๑ สรุปจำแนกประเภทสำนวน -->
                <div class="mt-2.5 avoid-break">
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
                                    <td style="font-weight: bold; padding: 3px 6px;">{{ number_format($val) }}</td>
                                    <td style="padding: 3px 6px;">{{ round(($val / ($statusStats['all'] ?: 1)) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold; background-color: #f8fafc;">
                                <td style="text-align: left; padding: 3px 6px;">รวมทั้งสิ้น</td>
                                <td style="padding: 3px 6px;">{{ number_format($statusStats['all']) }}</td>
                                <td style="padding: 3px 6px;">100.0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ตารางที่ ๒ บัญชีรายละเอียดสำนวนคดี -->
                @if($detailedCases->count() > 0)
                <div class="mt-3 avoid-break">
                    <p style="font-weight: bold; font-size: 14pt; margin-bottom: 2px;">ตารางที่ ๒ บัญชีรายละเอียดสำนวนคดีในรอบปีงบประมาณ ({{ $detailedCases->count() }} เรื่อง)</p>
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
                                    <td style="text-align: center; padding: 3px;">{{ $idx + 1 }}</td>
                                    <td style="font-weight: bold; white-space: nowrap; padding: 3px;">{{ $case->case_number }}</td>
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
            </div>

            <!-- ข้อ ๓. ข้อพิจารณาและข้อเสนอ -->
            <div class="avoid-break pt-1">
                <p style="text-indent: 2.5cm; font-size: 16pt; line-height: 1.25;">
                    <span style="font-weight: bold;">๓. ข้อพิจารณาและข้อเสนอ</span>&nbsp;&nbsp;กลุ่มงานกฎหมาย เห็นควรรายงานสรุปผลการดำเนินงานสำนวนคดีดังกล่าวต่อผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี เพื่อโปรดทราบผลการปฏิบัติงาน และมอบหมายข้อสั่งการตามที่เห็นสมควรต่อไป
                </p>
                <p style="text-indent: 2.5cm; font-size: 16pt; line-height: 1.25; font-weight: bold; margin-top: 4px;">
                    จึงเรียนมาเพื่อโปรดทราบและพิจารณา
                </p>
            </div>

            <!-- ลายมือชื่อนิติกรผู้จัดทำรายงาน -->
            <div class="avoid-break pt-3 text-right" style="font-size: 16pt;">
                <div class="inline-block text-center mr-8 space-y-0.5">
                    <p>ลงชื่อ ................................................................</p>
                    <p>( {{ Auth::user()->name ?? '................................................................' }} )</p>
                    <p style="font-size: 14pt;">นิติกร / ผู้จัดทำรายงาน</p>
                </div>
            </div>

            <!-- ส่วนความเห็นชอบและคำสั่งการของผู้อำนวยการ -->
            <div class="avoid-break border-t-2 border-black pt-3 mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4" style="font-size: 14pt;">
                <!-- ความเห็นหัวหน้ากลุ่มงาน -->
                <div class="space-y-1.5">
                    <p style="font-weight: bold;">ความเห็นของหัวหน้ากลุ่มงานกฎหมาย:</p>
                    <p>...........................................................................................</p>
                    <p>...........................................................................................</p>
                    <div class="text-center pt-1 space-y-0.5">
                        <p>ลงชื่อ ................................................................</p>
                        <p>( ................................................................ )</p>
                        <p style="font-size: 13pt;">หัวหน้ากลุ่มงานกฎหมาย</p>
                        <p style="font-size: 13pt;">วันที่ ...... / ............ / ..........</p>
                    </div>
                </div>

                <!-- คำสั่ง / ข้อสั่งการ ผอ.สคร.๑๐ -->
                <div class="space-y-1.5 border-t sm:border-t-0 sm:border-l sm:border-black pt-2 sm:pt-0 sm:pl-4">
                    <p style="font-weight: bold;">คำสั่ง / ข้อสั่งการ ผอ.สคร.๑๐ อุบลราชธานี:</p>
                    <div class="space-y-0.5 pl-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="rounded border-black">
                            <span>ทราบ</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="rounded border-black">
                            <span>ดำเนินการตามเสนอ</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="rounded border-black">
                            <span>อื่นๆ ......................................................................</span>
                        </label>
                    </div>
                    <div class="text-center pt-1 space-y-0.5">
                        <p>ลงชื่อ ................................................................</p>
                        <p>( ................................................................ )</p>
                        <p style="font-size: 13pt;">ผู้อำนวยการสำนักงานป้องกันควบคุมโรคที่ ๑๐ จังหวัดอุบลราชธานี</p>
                        <p style="font-size: 13pt;">วันที่ ...... / ............ / ..........</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    /**
     * ส่งออกเอกสารแบบบันทึกข้อความราชการเป็นไฟล์ Microsoft Word (.doc)
     * บังคับใช้ฟอนต์มาตรฐานราชการ TH Sarabun PSK / TH Sarabun New 16pt (หัวข้อ 29pt)
     * ระยะขอบมาตรฐาน: ซ้าย 3.0cm, ขวา 2.0cm, บน 2.5cm, ล่าง 2.0cm
     */
    function exportToWord() {
        const memoElement = document.getElementById('memo-document');
        if (!memoElement) {
            alert('ไม่พบข้อมูลเอกสารสำหรับส่งออก');
            return;
        }

        const clone = memoElement.cloneNode(true);

        // Pre-header for Microsoft Word formatting with native Thai Sarabun font-face
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
                        font-family: "TH Sarabun PSK";
                        panose-1: 2 11 5 0 4 2 0 2 0 4;
                        mso-font-charset: 222;
                        mso-generic-font-family: swiss;
                        mso-font-pitch: variable;
                        mso-font-signature: 16777219 0 0 0 65536 0;
                    }
                    @font-face {
                        font-family: "TH Sarabun New";
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

                    /* บังคับใช้ฟอนต์ TH Sarabun ทุกจุด */
                    *, body, div, p, span, table, th, td, h1, h2, h3, a, b, strong, i, em {
                        font-family: "TH Sarabun PSK", "TH Sarabun New", sans-serif !important;
                        mso-ascii-font-family: "TH Sarabun PSK" !important;
                        mso-hansi-font-family: "TH Sarabun PSK" !important;
                        mso-bidi-font-family: "TH Sarabun PSK" !important;
                        mso-fareast-font-family: "TH Sarabun PSK" !important;
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
