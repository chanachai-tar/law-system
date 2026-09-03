@extends('layouts.app')

@section('header_title')
    <i class="ri-folder-6-line text-indigo-600 text-lg" aria-hidden="true"></i>
    <span>รายการทะเบียนสำนวนกฎหมาย</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Actions & Flash Messages -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                ทะเบียนเลขคำสั่งและสำนวน
            </h2>
            <p class="text-xs text-slate-600 mt-0.5">
                จัดการ ติดตามสถานะความคืบหน้า กำกับกรอบเวลา และบันทึกคำสั่งทางกฎหมาย
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Export Excel Button -->
            <a href="{{ route('cases.export', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-700/20 transition-all active:scale-95">
                <i class="ri-file-excel-2-line text-base" aria-hidden="true"></i>
                <span>ส่งออก Excel</span>
            </a>

            <a href="{{ route('cases.create', ['law_type' => request('law_type')]) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition-all active:scale-95">
                <i class="ri-add-line text-base" aria-hidden="true"></i>
                <span>เพิ่มเลขคำสั่งใหม่</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <i class="ri-checkbox-circle-fill text-lg" aria-hidden="true"></i>
            </div>
            <p class="text-xs font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Category Tabs Navigation -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('cases.index') }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ !request('law_type') && !request('my_cases') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            ทั้งหมด ({{ $cases->total() }})
        </a>
        <a href="{{ route('cases.index', ['my_cases' => 1]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap flex items-center gap-1.5 {{ request('my_cases') == 1 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            <i class="ri-folder-user-line text-sm" aria-hidden="true"></i>
            <span>แฟ้มสำนวนของฉัน</span>
        </a>
        <a href="{{ route('cases.index', ['law_type' => 1]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 1 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            ตรวจสอบข้อเท็จจริง (ตส.)
        </a>
        <a href="{{ route('cases.index', ['law_type' => 2]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 2 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            ความรับผิดทางละเมิด (สล.)
        </a>
        <a href="{{ route('cases.index', ['law_type' => 3]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 3 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            สอบสวนวินัย (สว.)
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 p-5 sm:p-6">
        <form method="GET" action="{{ route('cases.index') }}">
            @if(request('my_cases'))
                <input type="hidden" name="my_cases" value="1">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label for="case_number_search" class="block mb-1.5 font-bold text-xs text-slate-700">ค้นหาเลขที่สำนวน / เรื่อง</label>
                    <div class="relative flex items-center">
                        <i class="ri-search-line absolute left-3.5 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                        <input type="text" name="case_number" id="case_number_search" value="{{ request('case_number') }}"
                            placeholder="เช่น ตส.xx, สล.xx, หรือชื่อเรื่อง..."
                            style="border-radius: 1rem !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                            class="w-full h-11 pl-9 pr-3.5 bg-slate-50 border border-slate-200/70 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label for="law_type_filter" class="block mb-1.5 font-bold text-xs text-slate-700">ประเภทสำนวน</label>
                    <div class="relative flex items-center">
                        <select name="law_type" id="law_type_filter"
                            style="border-radius: 1rem !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                            class="w-full h-11 pl-3.5 pr-8 bg-slate-50 border border-slate-200/70 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all appearance-none cursor-pointer">
                            <option value="">ทั้งหมดทุกประเภท</option>
                            <option value="1" {{ request('law_type') == '1' ? 'selected' : '' }}>ตรวจสอบข้อเท็จจริง (ตส.)</option>
                            <option value="2" {{ request('law_type') == '2' ? 'selected' : '' }}>ความรับผิดทางละเมิด (สล.)</option>
                            <option value="3" {{ request('law_type') == '3' ? 'selected' : '' }}>สอบสวนวินัย (สว.)</option>
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 text-slate-400 pointer-events-none text-sm" aria-hidden="true"></i>
                    </div>
                </div>

                <div>
                    <label for="due_filter_select" class="block mb-1.5 font-bold text-xs text-slate-700">กรอบเวลารายงาน (SLA)</label>
                    <div class="relative flex items-center">
                        <select name="due_filter" id="due_filter_select"
                            style="border-radius: 1rem !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                            class="w-full h-11 pl-3.5 pr-8 bg-slate-50 border border-slate-200/70 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all appearance-none cursor-pointer">
                            <option value="">กรอบเวลาทั้งหมด</option>
                            <option value="urgent" {{ request('due_filter') == 'urgent' ? 'selected' : '' }}>ใกล้ครบกำหนด (ใน 7 วัน)</option>
                            <option value="overdue" {{ request('due_filter') == 'overdue' ? 'selected' : '' }}>เกินกำหนดเวลา</option>
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-3 text-slate-400 pointer-events-none text-sm" aria-hidden="true"></i>
                    </div>
                </div>

                <div>
                    <label for="date_from_input" class="block mb-1.5 font-bold text-xs text-slate-700">วันที่สร้างสำนวน</label>
                    <input type="date" name="date_from" id="date_from_input" value="{{ request('date_from') }}"
                        style="border-radius: 1rem !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                        class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200/70 rounded-2xl text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                </div>

                <div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-md shadow-indigo-600/20 active:scale-95">
                            <i class="ri-search-2-line text-sm" aria-hidden="true"></i>
                            <span>ค้นหา</span>
                        </button>
                        <a href="{{ route('cases.index') }}"
                            title="ล้างตัวกรอง"
                            aria-label="ล้างตัวกรองการค้นหา"
                            class="w-11 h-11 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-2xl text-base border border-slate-200/70 transition active:scale-95">
                            <i class="ri-refresh-line" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-slate-800 font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                <i class="ri-file-list-3-line text-indigo-600 text-sm" aria-hidden="true"></i>
                รายการสำนวนทั้งหมด ({{ $cases->total() }} รายการ)
            </h3>
            <span class="text-[11px] text-slate-600 font-medium">คลิกแถวเพื่อดูความคืบหน้า (Timeline)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase border-b border-slate-200/70">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 tracking-wider">เลขที่สำนวน</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider">เรื่อง / รายละเอียด</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider">ประเภท</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider">กรอบเวลารายงาน</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider">ผู้รับผิดชอบ</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider text-center">สถานะ</th>
                        <th scope="col" class="px-6 py-3.5 tracking-wider text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cases as $case)
                        <tr class="hover:bg-indigo-50/40 transition cursor-pointer group select-none"
                            onclick="toggleTimeline('{{ $case->id }}', this)">

                            <!-- 1. เลขที่สำนวน -->
                            <td class="px-6 py-4 font-bold text-indigo-600 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        <i class="ri-arrow-right-s-line transform transition-transform duration-200 toggle-icon text-sm" aria-hidden="true"></i>
                                    </div>
                                    <span class="text-xs">{{ $case->case_number }}</span>
                                </div>
                            </td>

                            <!-- 2. เรื่อง / รายละเอียด -->
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-slate-800 font-medium line-clamp-2 leading-relaxed">
                                    {{ $case->subject }}
                                </p>
                                <div class="text-[10px] text-slate-600 mt-1 flex items-center gap-2">
                                    <span><i class="ri-calendar-line" aria-hidden="true"></i> {{ thaidate($case->created_at) }}</span>
                                    @if ($case->user)
                                        <span>•</span>
                                        <span><i class="ri-user-line" aria-hidden="true"></i> {{ $case->user->name }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 3. ประเภทสำนวน -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/80">
                                    {{ law_type($case->law_type) }}
                                </span>
                            </td>

                            <!-- 4. กรอบเวลารายงาน (SLA Due Date) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($case->status === 'completed')
                                    <span class="text-slate-600 text-[11px] font-medium">เสร็จสิ้นแล้ว</span>
                                @elseif($case->due_date)
                                    @php $days = $case->days_remaining; @endphp
                                    <div>
                                        <p class="text-slate-700 text-[11px] font-semibold">
                                            {{ thaidate($case->due_date, 'short') }}
                                        </p>
                                        @if($days < 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200 mt-0.5">
                                                <i class="ri-error-warning-line" aria-hidden="true"></i> เกิน {{ abs($days) }} วัน
                                            </span>
                                        @elseif($days <= 7)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 mt-0.5">
                                                <i class="ri-time-line" aria-hidden="true"></i> เหลือ {{ $days }} วัน
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-0.5">
                                                <i class="ri-checkbox-circle-line" aria-hidden="true"></i> เหลือ {{ $days }} วัน
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/80">
                                        <i class="ri-infinite-line text-xs" aria-hidden="true"></i> ไม่สิ้นสุด
                                    </span>
                                @endif
                            </td>

                            <!-- 5. ผู้นำเข้าข้อมูล -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-slate-700 text-xs font-medium">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-[10px]">
                                        {{ mb_substr($case->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    <span>{{ $case->user->name ?? 'ไม่ระบุ' }}</span>
                                </div>
                            </td>

                            <!-- 6. สถานะล่าสุด -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @php $lastStep = $case->steps->max('step_num'); @endphp
                                @if ($case->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                                        <i class="ri-checkbox-circle-fill text-xs" aria-hidden="true"></i> เสร็จสิ้น
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500" aria-hidden="true"></span>
                                        ครั้งที่ {{ $lastStep ?? 0 }}
                                    </span>
                                @endif
                            </td>

                            <!-- 7. จัดการ -->
                            <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                @if ($case->status === 'completed')
                                    <span class="text-[11px] font-medium text-slate-600 italic">ปิดงานแล้ว</span>
                                @else
                                    <div class="flex justify-center items-center gap-1.5">
                                        <a href="{{ route('cases.edit', $case->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition shadow-sm border border-indigo-100"
                                            title="ดำเนินการต่อ / บันทึกความคืบหน้า"
                                            aria-label="ดำเนินการต่อ สำนวน {{ $case->case_number }}">
                                            <i class="ri-edit-2-line text-sm" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('cases.destroy', $case->id) }}" method="POST"
                                            onsubmit="return confirm('ยืนยันการลบสำนวนนี้และไฟล์เอกสารทั้งหมด?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-xl transition shadow-sm border border-rose-100"
                                                title="ลบสำนวน"
                                                aria-label="ลบสำนวน {{ $case->case_number }}">
                                                <i class="ri-delete-bin-line text-sm" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>

                        <!-- Timeline Accordion Row -->
                        <tr id="timeline-{{ $case->id }}" class="hidden bg-slate-50/70 border-y border-slate-200/80">
                            <td colspan="7" class="px-6 py-6 sm:px-10">
                                <div class="max-w-4xl mx-auto space-y-6">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                            <i class="ri-history-line text-indigo-600 text-sm" aria-hidden="true"></i>
                                            ไทม์ไลน์ความคืบหน้าสำนวน ({{ $case->steps->count() }} ครั้ง)
                                        </h4>
                                        <span class="text-[11px] text-slate-600 font-semibold">เลขที่สำนวน: {{ $case->case_number }}</span>
                                    </div>

                                    <!-- Case Outcome Card (If closed) -->
                                    @if($case->status === 'completed' && ($case->outcome_summary || $case->penalty_type || $case->damage_amount))
                                        <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 space-y-2">
                                            <div class="flex items-center gap-2 font-bold text-xs text-emerald-800">
                                                <i class="ri-checkbox-circle-fill text-emerald-600 text-base" aria-hidden="true"></i>
                                                <span>สรุปผลคำวินิจฉัย / บทลงโทษหลังปิดสำนวน</span>
                                            </div>
                                            @if($case->outcome_summary)
                                                <p class="text-xs text-slate-700">{{ $case->outcome_summary }}</p>
                                            @endif
                                            <div class="flex flex-wrap gap-4 text-xs pt-1">
                                                @if($case->penalty_type)
                                                    <span class="text-slate-700"><strong>ผล/บทลงโทษ:</strong> {{ $case->penalty_type }}</span>
                                                @endif
                                                @if($case->damage_amount)
                                                    <span class="text-slate-700"><strong>ยอดเงินความเสียหาย/ชดใช้:</strong> {{ number_format($case->damage_amount, 2) }} บาท</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Timeline Container -->
                                    <div class="relative border-l-2 border-indigo-200 ml-4 space-y-6 pt-1">
                                        @forelse ($case->steps->sortBy('step_num') as $step)
                                            <div class="relative pl-7">
                                                <!-- Node Dot -->
                                                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-indigo-600 border-2 border-white shadow-sm z-10 flex items-center justify-center">
                                                    <span class="w-1 h-1 rounded-full bg-white"></span>
                                                </div>

                                                <!-- Content Box -->
                                                <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm space-y-3">
                                                    <div class="flex flex-wrap justify-between items-center gap-2">
                                                        <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-100">
                                                            ครั้งที่ {{ $step->step_num }}
                                                        </span>
                                                        <div class="flex items-center gap-3 text-[11px] text-slate-600">
                                                            <span><i class="ri-user-3-line" aria-hidden="true"></i> {{ $step->user->name ?? 'ไม่ระบุ' }}</span>
                                                            <span>•</span>
                                                            <span><i class="ri-calendar-line" aria-hidden="true"></i> {{ thaidate($step->created_at) }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="p-3.5 bg-slate-50 rounded-xl text-xs text-slate-700 leading-relaxed break-words">
                                                        {{ $step->description }}
                                                    </div>

                                                    <!-- Files -->
                                                    @if ($step->files->count() > 0)
                                                        <div class="flex flex-wrap gap-2 pt-1">
                                                            @foreach ($step->files as $file)
                                                                <a href="{{ route('files.view', base64_encode($file->file_path)) }}"
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-white text-rose-600 rounded-xl border border-rose-200/80 text-[11px] font-semibold hover:bg-rose-600 hover:text-white transition shadow-sm">
                                                                    <i class="ri-file-pdf-fill text-sm" aria-hidden="true"></i>
                                                                    <span class="truncate max-w-[200px]">{{ $file->file_name }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="pl-7 text-slate-600 text-xs italic">
                                                ยังไม่มีการบันทึกขั้นตอนดำเนินการ
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Bottom Action: Close Case -->
                                    <div class="pt-4 border-t border-slate-200/60 flex justify-center">
                                        @if ($case->status != 'completed')
                                            <button type="button"
                                                data-id="{{ $case->id }}" data-casenumber="{{ $case->case_number }}" onclick="openCloseModal(this.dataset.id, this.dataset.casenumber)"
                                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-white hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 rounded-xl text-xs font-semibold transition-all shadow-sm active:scale-95">
                                                <i class="ri-checkbox-circle-line text-base" aria-hidden="true"></i>
                                                <span>ปิดสำนวนและสิ้นสุดการดำเนินการ</span>
                                            </button>
                                        @else
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-xs font-semibold">
                                                <i class="ri-checkbox-circle-fill text-sm" aria-hidden="true"></i>
                                                <span>สำนวนนี้ดำเนินการเสร็จสิ้นเรียบร้อยแล้ว</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-600">
                                <i class="ri-inbox-2-line text-4xl mb-2 block text-slate-400" aria-hidden="true"></i>
                                <p class="text-xs font-medium">ไม่พบข้อมูลสำนวนตามเงื่อนไขที่ค้นหา</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($cases->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $cases->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Global Modal: ปิดสำนวนพร้อมบันทึกผลการพิจารณา -->
    <div id="globalCloseCaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 text-left border border-slate-100 space-y-4 animate-in fade-in zoom-in-95 duration-150" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl">
                        <i class="ri-checkbox-circle-line" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 id="closeCaseModalTitle" class="text-sm font-bold text-slate-900">
                            บันทึกปิดสำนวน
                        </h3>
                        <p class="text-[11px] text-slate-600 font-medium">ระบุสรุปผลการพิจารณาเพื่อสิ้นสุดการดำเนินการ</p>
                    </div>
                </div>
                <button type="button" onclick="closeCaseModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1" aria-label="ปิดหน้าต่าง">
                    <i class="ri-close-line" aria-hidden="true"></i>
                </button>
            </div>

            <form id="closeCaseForm" method="POST" action="" class="space-y-3.5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="close_outcome_summary" class="block text-xs font-bold text-slate-700 mb-1">สรุปผลการพิจารณา / ผลคำวินิจฉัย</label>
                    <textarea name="outcome_summary" id="close_outcome_summary" rows="2" placeholder="เช่น คณะกรรมการสอบสวนมีความเห็นว่า..."
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="close_penalty_type" class="block text-[11px] font-bold text-slate-700 mb-1">ผล / บทลงโทษ (ถ้ามี)</label>
                        <input type="text" name="penalty_type" id="close_penalty_type" placeholder="เช่น ภาคทัณฑ์, ยุติเรื่อง"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label for="close_damage_amount" class="block text-[11px] font-bold text-slate-700 mb-1">ยอดเงินชดใช้ (บาท)</label>
                        <input type="number" step="0.01" name="damage_amount" id="close_damage_amount" placeholder="เช่น 50000.00"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>

                <div class="flex gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeCaseModal()"
                        class="flex-1 px-4 py-2.5 text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-200 transition active:scale-95">
                        ยืนยันปิดสำนวน
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function toggleTimeline(caseId, rowElement) {
        const timelineRow = document.getElementById('timeline-' + caseId);
        if (!timelineRow) return;

        const icon = rowElement.querySelector('.toggle-icon');

        if (timelineRow.classList.contains('hidden')) {
            timelineRow.classList.remove('hidden');
            if (icon) {
                icon.classList.add('rotate-90');
            }
        } else {
            timelineRow.classList.add('hidden');
            if (icon) {
                icon.classList.remove('rotate-90');
            }
        }
    }

    function openCloseModal(caseId, caseNumber) {
        const modal = document.getElementById('globalCloseCaseModal');
        const form = document.getElementById('closeCaseForm');
        const titleEl = document.getElementById('closeCaseModalTitle');

        if (modal && form) {
            form.action = "{{ url('/cases') }}/" + caseId + "/close";
            if (titleEl) {
                titleEl.textContent = "บันทึกปิดสำนวน: " + caseNumber;
            }
            modal.classList.remove('hidden');
        }
    }

    function closeCaseModal() {
        const modal = document.getElementById('globalCloseCaseModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Close on clicking backdrop
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('globalCloseCaseModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeCaseModal();
                }
            });
        }
    });
</script>
@endpush
