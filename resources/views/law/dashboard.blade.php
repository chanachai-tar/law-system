@extends('layouts.app')

@section('header_title')
    <i class="ri-dashboard-3-line text-indigo-600 text-lg" aria-hidden="true"></i>
    <span>แดชบอร์ดภาพรวมระบบ</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- 1. Welcome Banner & Fiscal Year Filter -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-36 -top-12 w-48 h-48 bg-sky-400/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-indigo-100 text-xs font-semibold border border-white/15 shadow-sm">
                    <img src="{{ asset('images/lss_logo_rounded.png') }}" alt="โลโก้ ODPC10-LSS" class="w-4 h-4 object-contain">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" aria-hidden="true"></span>
                    <span>ODPC10 Legal Syllabus System</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    ภาพรวมระบบงานสำนวนกฎหมาย
                </h2>
                <p class="text-indigo-200 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                    ศูนย์กลางการติดตามสำนวน กำกับกรอบเวลาตามระเบียบ และจัดการคำสั่งแต่งตั้งคณะกรรมการ
                </p>
            </div>

            <!-- Fiscal Year Filter & Quick Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Fiscal Year Dropdown (Modern Sleek Pill) -->
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
                    <div class="relative flex items-center bg-white hover:bg-slate-50 text-slate-800 rounded-2xl shadow-md border border-slate-200/90 pl-3.5 pr-8 py-2 transition-all duration-200 group">
                        <i class="ri-calendar-2-line text-indigo-600 text-sm mr-2 flex-shrink-0" aria-hidden="true"></i>
                        <label for="dashboard_fiscal_year" class="text-xs font-bold text-slate-700 mr-1.5 whitespace-nowrap cursor-pointer select-none">
                            ปีงบประมาณ:
                        </label>
                        <select name="fiscal_year" id="dashboard_fiscal_year" onchange="this.form.submit()" aria-label="เลือกปีงบประมาณ"
                            style="border: none !important; box-shadow: none !important; background-color: transparent !important;"
                            class="bg-transparent border-0 p-0 text-xs font-extrabold text-indigo-700 outline-none focus:outline-none focus:ring-0 cursor-pointer appearance-none">
                            <option value="" class="bg-white text-slate-800 font-semibold" {{ !$selectedYear ? 'selected' : '' }}>
                                ทั้งหมดทุกปี
                            </option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" class="bg-white text-slate-800 font-semibold" {{ $selectedYear == $yr ? 'selected' : '' }}>
                                    พ.ศ. {{ $yr }}
                                </option>
                            @endforeach
                        </select>
                        <i class="ri-arrow-down-s-line absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-indigo-600 text-sm pointer-events-none transition-colors" aria-hidden="true"></i>
                    </div>
                </form>

                <a href="{{ route('cases.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
                    <i class="ri-add-circle-line text-base" aria-hidden="true"></i>
                    <span>เพิ่มสำนวนใหม่</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 1.1 Urgent Due Date Alert (If any) -->
    @if($stats['overdue_count'] > 0 || $stats['urgent_count'] > 0)
        <div class="bg-gradient-to-r from-rose-500/10 via-amber-500/10 to-indigo-500/10 border border-amber-300/80 rounded-3xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-md shadow-amber-500/30">
                    <i class="ri-alarm-warning-fill" aria-hidden="true"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>แจ้งเตือนกรอบเวลารายงานผลตามระเบียบ</span>
                        @if($stats['overdue_count'] > 0)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-600 text-white">เกินกำหนด {{ $stats['overdue_count'] }} เรื่อง</span>
                        @endif
                        @if($stats['urgent_count'] > 0)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-600 text-white">ใกล้ครบกำหนดใน 7 วัน {{ $stats['urgent_count'] }} เรื่อง</span>
                        @endif
                    </h4>
                    <p class="text-xs text-slate-600 mt-0.5">มีสำนวนคดีที่ต้องเร่งรัดติดตามความคืบหน้าเพื่อเสนอรายงานตามกรอบเวลา</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($stats['overdue_count'] > 0)
                    <a href="{{ route('cases.index', ['due_filter' => 'overdue']) }}"
                        class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        ดูสำนวนที่เกินกำหนด
                    </a>
                @endif
                @if($stats['urgent_count'] > 0)
                    <a href="{{ route('cases.index', ['due_filter' => 'urgent']) }}"
                        class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        ดูสำนวนที่ใกล้ครบกำหนด
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- 2. Stat Summary Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Card 1: สำนวนทั้งหมด -->
        <a href="{{ route('cases.index') }}" class="bg-white p-5 sm:p-6 rounded-3xl shadow-sm border border-slate-200/70 hover:shadow-md hover:border-indigo-300 transition-all duration-200 group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">สำนวนทั้งหมด</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1.5 group-hover:text-indigo-600 transition-colors">
                        {{ number_format($stats['all']) }}
                    </h3>
                    <p class="text-[11px] text-slate-600 mt-1 font-medium flex items-center gap-1">
                        <span>ดูรายการทั้งหมด</span>
                        <i class="ri-arrow-right-s-line text-indigo-600 group-hover:translate-x-0.5 transition-transform" aria-hidden="true"></i>
                    </p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-200 shadow-sm border border-indigo-100">
                    <i class="ri-folders-line" aria-hidden="true"></i>
                </div>
            </div>
        </a>

        <!-- Card 2: อยู่ระหว่างดำเนินการ -->
        <a href="{{ route('cases.pending') }}" class="bg-white p-5 sm:p-6 rounded-3xl shadow-sm border border-slate-200/70 hover:shadow-md hover:border-amber-300 transition-all duration-200 group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">อยู่ระหว่างดำเนินการ</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1.5">
                        {{ number_format($stats['pending']) }}
                    </h3>
                    <p class="text-[11px] text-amber-700 mt-1 font-medium flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500" aria-hidden="true"></span>
                        <span>กำลังติดตามสถานะ</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-200 shadow-sm border border-amber-100">
                    <i class="ri-time-line" aria-hidden="true"></i>
                </div>
            </div>
        </a>

        <!-- Card 3: ดำเนินการเสร็จสิ้น -->
        <a href="{{ route('cases.completed') }}" class="bg-white p-5 sm:p-6 rounded-3xl shadow-sm border border-slate-200/70 hover:shadow-md hover:border-emerald-300 transition-all duration-200 group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">ดำเนินการเสร็จสิ้น</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1.5">
                        {{ number_format($stats['completed']) }}
                    </h3>
                    <p class="text-[11px] text-emerald-700 mt-1 font-medium flex items-center gap-1">
                        <i class="ri-checkbox-circle-line text-xs" aria-hidden="true"></i>
                        <span>ปิดสำนวนเรียบร้อย</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200 shadow-sm border border-emerald-100">
                    <i class="ri-checkbox-circle-line" aria-hidden="true"></i>
                </div>
            </div>
        </a>

        <!-- Card 4: คำสั่งแต่งตั้ง -->
        <a href="{{ route('orders.index') }}" class="bg-white p-5 sm:p-6 rounded-3xl shadow-sm border border-slate-200/70 hover:shadow-md hover:border-sky-300 transition-all duration-200 group block">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-sky-700 uppercase tracking-wider">แฟ้มคำสั่งแต่งตั้ง</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-sky-600 mt-1.5">
                        {{ number_format($stats['orders_count'] ?? 0) }}
                    </h3>
                    <p class="text-[11px] text-slate-600 mt-1 font-medium flex items-center gap-1">
                        <span>คำสั่งในระบบทั้งหมด</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-sky-600 group-hover:text-white transition-all duration-200 shadow-sm border border-sky-100">
                    <i class="ri-file-list-3-line" aria-hidden="true"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- 3. Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Chart: Doughnut -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-slate-200/70 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <i class="ri-pie-chart-2-line text-indigo-600 text-base" aria-hidden="true"></i>
                        <span>สัดส่วนประเภทสำนวน</span>
                    </h3>
                    <span class="text-[11px] text-slate-600 font-medium">รวม {{ number_format($stats['all']) }} เรื่อง</span>
                </div>
                <div class="relative flex justify-center items-center py-2">
                    <canvas id="caseTypeChart" role="img" aria-label="แผนภูมิวงกลมแสดงสัดส่วนประเภทสำนวน" class="max-h-[220px]"></canvas>
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between text-xs">
                <span class="text-slate-600 text-[11px]">อัปเดตแบบเรียลไทม์</span>
                <a href="{{ route('cases.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1">
                    ดูรายการทั้งหมด <i class="ri-arrow-right-line" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <!-- Breakdown List with Progress Bars -->
        <div class="lg:col-span-3 bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-slate-200/70">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <i class="ri-bar-chart-grouped-line text-indigo-600 text-base" aria-hidden="true"></i>
                    <span>รายละเอียดแยกตามประเภทสำนวน</span>
                </h3>
                <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">
                    {{ count($stats['types']) }} ประเภท
                </span>
            </div>

            <div class="space-y-4">
                @php
                    $colors = [
                        ['bg' => 'bg-indigo-600', 'text' => 'text-indigo-600', 'light' => 'bg-indigo-50'],
                        ['bg' => 'bg-amber-500', 'text' => 'text-amber-600', 'light' => 'bg-amber-50'],
                        ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'light' => 'bg-emerald-50'],
                        ['bg' => 'bg-sky-500', 'text' => 'text-sky-600', 'light' => 'bg-sky-50'],
                    ];
                @endphp

                @forelse ($stats['types'] as $index => $type)
                    @php
                        $percent = $stats['all'] > 0 ? ($type->total / $stats['all']) * 100 : 0;
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 hover:bg-indigo-50/30 transition">
                        <div class="flex justify-between items-center text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $color['bg'] }}" aria-hidden="true"></span>
                                <span class="font-bold text-slate-700">{{ $type->law_type }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800">{{ number_format($type->total) }} สำนวน</span>
                                <span class="text-[11px] font-medium text-slate-600">({{ round($percent, 1) }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                            <div class="{{ $color['bg'] }} h-2 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs italic">
                        ยังไม่มีข้อมูลสำนวนในระบบ
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. Recent Cases Table & Quick Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Cases (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-history-line text-indigo-600 text-sm" aria-hidden="true"></i>
                    สำนวนที่บันทึกล่าสุด
                </h3>
                <div class="flex items-center gap-3">
                    <a href="{{ route('cases.export') }}" class="text-[11px] font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1">
                        <i class="ri-file-excel-2-line" aria-hidden="true"></i> ส่งออก Excel
                    </a>
                    <a href="{{ route('cases.index') }}" class="text-[11px] font-bold text-indigo-700 hover:text-indigo-900 hover:underline">
                        ดูทั้งหมด ({{ $stats['all'] }})
                    </a>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentCases as $case)
                    <div class="p-4 sm:px-6 hover:bg-slate-50 transition flex items-center justify-between gap-4">
                        <div class="min-w-0 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-indigo-700 text-xs">{{ $case->case_number }}</span>
                                <span class="px-2 py-0.2 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ law_type($case->law_type) }}
                                </span>
                                @if($case->due_date && $case->status !== 'completed')
                                    @php
                                        $days = $case->days_remaining;
                                    @endphp
                                    @if($days < 0)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            เกินกำหนด {{ abs($days) }} วัน
                                        </span>
                                    @elseif($days <= 7)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            เหลือ {{ $days }} วัน
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <p class="text-xs text-slate-700 truncate font-medium">
                                {{ $case->subject }}
                            </p>
                            <p class="text-[10px] text-slate-600">
                                {{ thaidate($case->created_at) }} • {{ $case->user->name ?? 'ไม่ระบุ' }}
                            </p>
                        </div>

                        <div class="flex-shrink-0">
                            @if($case->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <i class="ri-checkbox-circle-fill text-xs" aria-hidden="true"></i> เสร็จสิ้น
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600" aria-hidden="true"></span>
                                    ดำเนินการ
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-600 text-xs italic">
                        ยังไม่มีสำนวนในระบบ
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Appointment Orders (1 Column) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-file-list-3-line text-sky-700 text-sm" aria-hidden="true"></i>
                    คำสั่งแต่งตั้งล่าสุด
                </h3>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('orders.export') }}" class="text-[11px] font-bold text-emerald-800 hover:text-emerald-950 flex items-center gap-1">
                        <i class="ri-file-excel-2-line" aria-hidden="true"></i> Excel
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-[11px] font-bold text-sky-800 hover:text-sky-950 hover:underline">
                        ดูทั้งหมด
                    </a>
                </div>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <div class="p-4 hover:bg-slate-50 transition space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">{{ $order->order_number }}</span>
                            <span class="text-[10px] text-slate-500">{{ thaidate($order->order_date, 'short') }}</span>
                        </div>
                        <p class="text-xs text-slate-600 truncate">
                            {{ $order->subject }}
                        </p>
                        <p class="text-[10px] text-slate-500">
                            {{ $order->owner }}
                        </p>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs italic">
                        ยังไม่มีคำสั่งแต่งตั้ง
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('caseTypeChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const labels = {!! json_encode($stats['types']->pluck('law_type')) !!};
        const data = {!! json_encode($stats['types']->pluck('total')) !!};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels.length ? labels : ['ไม่มีข้อมูล'],
                datasets: [{
                    data: data.length ? data : [1],
                    backgroundColor: [
                        '#4f46e5', // Indigo 600
                        '#f59e0b', // Amber 500
                        '#10b981', // Emerald 500
                        '#0ea5e9', // Sky 500
                        '#8b5cf6'  // Violet 500
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: {
                                family: "'Prompt', sans-serif",
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
