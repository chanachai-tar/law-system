@extends('layouts.app')

@section('header_title')
    <i class="ri-folder-zip-line text-indigo-600 text-lg"></i>
    <span>แฟ้มเอกสารกลุ่มที่เกี่ยวกับกฎหมาย</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                แฟ้มเอกสารกลุ่มที่เกี่ยวกับกฎหมาย
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                ศูนย์รวบรวมและจัดหมวดหมู่แฟ้มเอกสารตามกลุ่มงานกฎหมาย (ตส. / สล. / สว.) และคำสั่งแต่งตั้ง
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/80 rounded-xl text-xs font-semibold shadow-sm transition active:scale-95">
                <i class="ri-file-list-3-line text-indigo-600"></i>
                <span>ดูแฟ้มคำสั่งแต่งตั้ง</span>
            </a>
            <a href="{{ route('cases.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition active:scale-95">
                <i class="ri-add-line text-base"></i>
                <span>เพิ่มสำนวนใหม่</span>
            </a>
        </div>
    </div>

    <!-- Legal Group Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. ตส. -->
        <a href="{{ route('cases.files', ['law_type' => 1]) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('law_type') == 1 ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200/70 hover:border-indigo-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-search-eye-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    กลุ่ม ตส.
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                ตรวจสอบข้อเท็จจริง
            </p>
            <p class="text-2xl font-black text-slate-800 mt-1">
                {{ $stats['ts'] }} <span class="text-xs font-normal text-slate-400">ไฟล์เอกสาร</span>
            </p>
        </a>

        <!-- 2. สล. -->
        <a href="{{ route('cases.files', ['law_type' => 2]) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('law_type') == 2 ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200/70 hover:border-amber-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-scales-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                    กลุ่ม สล.
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-amber-600 transition-colors">
                ความรับผิดทางละเมิด
            </p>
            <p class="text-2xl font-black text-slate-800 mt-1">
                {{ $stats['sl'] }} <span class="text-xs font-normal text-slate-400">ไฟล์เอกสาร</span>
            </p>
        </a>

        <!-- 3. สว. -->
        <a href="{{ route('cases.files', ['law_type' => 3]) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('law_type') == 3 ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200/70 hover:border-emerald-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-shield-user-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                    กลุ่ม สว.
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">
                สอบสวนวินัย
            </p>
            <p class="text-2xl font-black text-slate-800 mt-1">
                {{ $stats['sw'] }} <span class="text-xs font-normal text-slate-400">ไฟล์เอกสาร</span>
            </p>
        </a>

        <!-- 4. คำสั่งแต่งตั้ง -->
        <a href="{{ route('orders.index') }}"
            class="bg-white p-5 rounded-3xl border border-slate-200/70 hover:border-sky-300 hover:shadow-md transition-all duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-file-list-3-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                    คำสั่ง
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-sky-600 transition-colors">
                คำสั่งแต่งตั้งคณะกรรมการ
            </p>
            <p class="text-2xl font-black text-slate-800 mt-1">
                {{ $stats['orders'] }} <span class="text-xs font-normal text-slate-400">คำสั่งในระบบ</span>
            </p>
        </a>
    </div>

    <!-- Category Tabs Navigation -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('cases.files') }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ !request('law_type') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            แฟ้มเอกสารทั้งหมด ({{ $stats['all'] }})
        </a>
        <a href="{{ route('cases.files', ['law_type' => 1]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 1 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            กลุ่ม ตส. ตรวจสอบข้อเท็จจริง ({{ $stats['ts'] }})
        </a>
        <a href="{{ route('cases.files', ['law_type' => 2]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 2 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            กลุ่ม สล. ความรับผิดทางละเมิด ({{ $stats['sl'] }})
        </a>
        <a href="{{ route('cases.files', ['law_type' => 3]) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('law_type') == 3 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            กลุ่ม สว. สอบสวนวินัย ({{ $stats['sw'] }})
        </a>
    </div>

    <!-- Search / Filter Card -->
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/70">
        <form method="GET" action="{{ route('cases.files') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('law_type'))
                <input type="hidden" name="law_type" value="{{ request('law_type') }}">
            @endif

            <div class="relative flex-1">
                <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ค้นหาชื่อไฟล์, เลขที่สำนวน, หรือหัวเรื่องกลุ่มกฎหมาย..."
                    class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
            </div>

            <button type="submit"
                class="h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 active:scale-95">
                <i class="ri-search-line text-sm"></i>
                <span>ค้นหาเอกสาร</span>
            </button>

            @if(request('search') || request('law_type'))
                <a href="{{ route('cases.files') }}"
                    class="h-11 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 active:scale-95">
                    <i class="ri-refresh-line text-sm"></i>
                    <span>ล้างการค้นหา</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Files Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase border-b border-slate-200/70">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">ชื่อเอกสาร PDF</th>
                        <th scope="col" class="px-6 py-3.5">กลุ่มงานกฎหมาย</th>
                        <th scope="col" class="px-6 py-3.5">เลขที่สำนวน / เรื่อง</th>
                        <th scope="col" class="px-6 py-3.5 text-center">ขั้นตอน</th>
                        <th scope="col" class="px-6 py-3.5">เจ้าหน้าที่ผู้แนบ</th>
                        <th scope="col" class="px-6 py-3.5">วันที่แนบ</th>
                        <th scope="col" class="px-6 py-3.5 text-center">เปิดเอกสาร</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($files as $file)
                        @php
                            $step = $file->step;
                            $case = $step?->legalCase;
                            $lawType = $case?->law_type;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl flex-shrink-0 border border-rose-100 shadow-sm">
                                        <i class="ri-file-pdf-fill"></i>
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <p class="font-bold text-slate-800 truncate" title="{{ $file->file_name }}">
                                            {{ $file->file_name ?? 'เอกสารแนบ' }}
                                        </p>
                                        <span class="text-[10px] text-slate-400">เอกสาร PDF</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lawType == 1)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-200">
                                        <i class="ri-search-eye-line"></i> ตรวจสอบข้อเท็จจริง (ตส.)
                                    </span>
                                @elseif($lawType == 2)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">
                                        <i class="ri-scales-line"></i> ความรับผิดทางละเมิด (สล.)
                                    </span>
                                @elseif($lawType == 3)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">
                                        <i class="ri-shield-user-line"></i> สอบสวนวินัย (สว.)
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[10px]">- ไม่ระบุ -</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($case)
                                    <div>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $case->case_number }}
                                        </span>
                                        <p class="font-semibold text-slate-700 mt-1 line-clamp-1" title="{{ $case->subject }}">
                                            {{ $case->subject }}
                                        </p>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">- ไม่พบข้อมูลสำนวน -</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($step)
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-[11px] border border-slate-200">
                                        ครั้งที่ {{ $step->step_num }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                                    <i class="ri-user-3-line text-indigo-500"></i>
                                    <span>{{ $step?->user?->name ?? 'ไม่ระบุ' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-medium">
                                {{ thaidate($file->created_at) }}
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl font-bold text-xs border border-indigo-200/80 transition shadow-sm active:scale-95">
                                    <i class="ri-file-pdf-fill"></i>
                                    <span>เปิดอ่าน PDF</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-14 h-14 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="ri-folder-open-line"></i>
                                </div>
                                <p class="font-bold text-xs text-slate-600">ไม่พบไฟล์เอกสารในกลุ่มกฎหมายนี้</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">ท่านสามารถแนบไฟล์ PDF เพิ่มเติมได้ในหน้าบันทึกความคืบหน้าสำนวน</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($files->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $files->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
