<!DOCTYPE html>
<html lang="th" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ระบบงานสารบรรณและทะเบียนสำนวนกฎหมาย สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี">
    <meta name="theme-color" content="#4f46e5">

    <title>{{ config('app.name', 'ระบบงานสำนวนกฎหมาย') }}</title>

    <!-- Favicon & App Icons (Rounded Corners) -->
    <link rel="icon" type="image/png" href="{{ asset('images/lss_logo_rounded.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/lss_logo_rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/lss_logo_rounded.png') }}">

    <!-- Google Fonts: Prompt, Noto Sans Thai & Sarabun (TH Sarabun) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700;800&display=swap">
    </noscript>

    <!-- Remix Icons (Minified) -->
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.min.css">
    </noscript>

    <!-- Chart.js (Deferred) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Prompt', 'Noto Sans Thai', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200/80 shadow-sm flex flex-col fixed inset-y-0 left-0 z-50" aria-label="แถบเมนูหลัก">
            <!-- Brand Logo Header -->
            <div class="p-5 border-b border-slate-100 bg-gradient-to-br from-indigo-50/50 via-white to-white">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group" aria-label="หน้าหลักแดชบอร์ด">
                    <img src="{{ asset('images/lss_logo_rounded.png') }}" alt="โลโก้ ODPC10-LSS" class="w-10 h-10 object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">

                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-extrabold text-slate-800 tracking-tight">ODPC10 <span class="text-indigo-600">LSS</span></span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 bg-indigo-50 text-indigo-700 rounded-md border border-indigo-100/80">
                                {{ app_version() }}
                            </span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 leading-tight">ระบบงานสำนวนกฎหมาย</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 text-slate-600 text-sm overflow-y-auto" aria-label="เมนูระบบ">
                <!-- หมวด: ภาพรวม -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">ภาพรวมระบบ</div>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-dashboard-3-line text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>แดชบอร์ด</span>
                </a>

                <div class="pt-3 pb-1">
                    <div class="h-px bg-slate-100 mx-2"></div>
                </div>

                <!-- หมวด: คำสั่งแต่งตั้งคณะกรรมการ -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">คำสั่งแต่งตั้งคณะกรรมการ</div>

                <a href="{{ route('orders.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-file-list-3-line text-base {{ request()->routeIs('orders.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>คำสั่งแต่งตั้งคณะกรรมการ</span>
                </a>

                <div class="pt-3 pb-1">
                    <div class="h-px bg-slate-100 mx-2"></div>
                </div>

                <!-- หมวด: ทะเบียนสำนวนกฎหมาย -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">ทะเบียนสำนวนกฎหมาย</div>

                <!-- ตส. (ID=1) -->
                <a href="{{ route('cases.index', ['law_type' => 1]) }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request('law_type') == 1 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-search-eye-line text-base {{ request('law_type') == 1 ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>ตรวจสอบข้อเท็จจริง (ตส.)</span>
                </a>

                <!-- สล. (ID=2) -->
                <a href="{{ route('cases.index', ['law_type' => 2]) }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request('law_type') == 2 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-scales-line text-base {{ request('law_type') == 2 ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>ความรับผิดทางละเมิด (สล.)</span>
                </a>

                <!-- สว. (ID=3) -->
                <a href="{{ route('cases.index', ['law_type' => 3]) }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request('law_type') == 3 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-shield-user-line text-base {{ request('law_type') == 3 ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>สอบสวนวินัย (สว.)</span>
                </a>

                <!-- ทั้งหมด -->
                <a href="{{ route('cases.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('cases.index') && !request('law_type') && !request('my_cases') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-folder-6-line text-base {{ request()->routeIs('cases.index') && !request('law_type') && !request('my_cases') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>รายการสำนวนทั้งหมด</span>
                </a>

                <div class="pt-3 pb-1">
                    <div class="h-px bg-slate-100 mx-2"></div>
                </div>

                <!-- หมวด: สถานะสำนวน -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">สถานะการดำเนินงาน</div>

                <a href="{{ route('cases.pending') }}"
                    class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('cases.pending') ? 'bg-amber-500 text-white shadow-sm shadow-amber-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-time-line text-base {{ request()->routeIs('cases.pending') ? 'text-white' : 'text-amber-500' }}" aria-hidden="true"></i>
                        <span>อยู่ระหว่างดำเนินการ</span>
                    </div>
                </a>

                <a href="{{ route('cases.completed') }}"
                    class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('cases.completed') ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-checkbox-circle-line text-base {{ request()->routeIs('cases.completed') ? 'text-white' : 'text-emerald-500' }}" aria-hidden="true"></i>
                        <span>สำนวนที่แล้วเสร็จ</span>
                    </div>
                </a>

                <div class="pt-3 pb-1">
                    <div class="h-px bg-slate-100 mx-2"></div>
                </div>

                <!-- หมวด: เอกสารและรายงาน -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">รายงาน & คลังข้อมูล</div>

                <a href="{{ route('reports.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-bar-chart-box-line text-base {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>รายงานสรุปภาพรวม</span>
                </a>

                <a href="{{ route('regulations.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('regulations.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="ri-book-read-line text-base {{ request()->routeIs('regulations.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                    <span>คลังระเบียบ & กฎหมาย</span>
                </a>

                <div class="pt-3 pb-1">
                    <div class="h-px bg-slate-100 mx-2"></div>
                </div>

                <!-- หมวด: แฟ้มเอกสารกลุ่มงานกฎหมาย (เจ้าหน้าที่) -->
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5 flex items-center justify-between">
                    <span>แฟ้มเอกสารกลุ่มกฎหมาย</span>
                </div>

                <!-- 1. แฟ้มสำนวนของฉัน -->
                <a href="{{ route('cases.index', ['my_cases' => 1]) }}"
                    class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request('my_cases') == 1 ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-folder-user-line text-base {{ request('my_cases') == 1 ? 'text-white' : 'text-indigo-500' }}" aria-hidden="true"></i>
                        <span>แฟ้มสำนวนของฉัน</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request('my_cases') == 1 ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-600' }}">
                        {{ \App\Models\LegalCase::where('user_id', Auth::id())->count() }}
                    </span>
                </a>

                <!-- 2. แฟ้มเอกสารกลุ่มกฎหมาย PDF -->
                <a href="{{ route('cases.files') }}"
                    class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('cases.files') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-folder-zip-line text-base {{ request()->routeIs('cases.files') ? 'text-white' : 'text-rose-500' }}" aria-hidden="true"></i>
                        <span>คลังแฟ้มเอกสาร PDF</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request()->routeIs('cases.files') ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-600' }}">
                        {{ \App\Models\CaseFile::count() }}
                    </span>
                </a>

                @if (in_array(Auth::user()?->role, ['admin', 'super_admin']))
                    <div class="pt-3 pb-1">
                        <div class="h-px bg-slate-100 mx-2"></div>
                    </div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-1.5">ผู้ดูแลระบบ</div>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="ri-user-settings-line text-base {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                        <span>ตั้งค่าเจ้าหน้าที่ผู้ใช้</span>
                    </a>
                    <a href="{{ route('settings.telegram.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('settings.telegram.*') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="ri-telegram-fill text-base {{ request()->routeIs('settings.telegram.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-600' }}" aria-hidden="true"></i>
                        <span>ตั้งค่าการแจ้งเตือน Telegram</span>
                    </a>
                @endif
                
                @if (Auth::user()?->role === 'super_admin')
                    <div class="pt-3 pb-1">
                        <div class="h-px bg-slate-100 mx-2"></div>
                    </div>
                    <div class="text-[10px] font-bold text-rose-500 uppercase tracking-wider px-3 mb-1.5">Super Admin</div>
                    <a href="{{ route('superadmin.index') }}"
                        class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all duration-200 group {{ request()->routeIs('superadmin.*') ? 'bg-rose-600 text-white shadow-sm shadow-rose-200' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="ri-shield-star-fill text-base {{ request()->routeIs('superadmin.*') ? 'text-white' : 'text-slate-500 group-hover:text-rose-600' }}" aria-hidden="true"></i>
                        <span>การจัดการระบบขั้นสูง</span>
                    </a>
                @endif

                                </nav>

            <!-- Sidebar Footer -->
            <div class="mt-auto border-t border-slate-100 bg-slate-50/70 p-3.5 text-center">
                <p class="text-[11px] font-semibold text-slate-600">สำนักงานป้องกันควบคุมโรคที่ 10</p>
                <p class="text-[10px] text-slate-500">จ.อุบลราชธานี • ODPC10 LSS</p>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col ml-64 min-h-screen bg-slate-50">
            <!-- Top Header Navbar -->
            <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 h-16 flex items-center justify-between px-6 sm:px-8 sticky top-0 z-40">
                <!-- Left: Title / Breadcrumbs -->
                <div class="flex items-center gap-3">
                    <h1 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        @yield('header_title', 'ระบบงานสำนวนกฎหมาย')
                    </h1>
                </div>

                <!-- Right: Quick Info & User Profile -->
                <div class="flex items-center gap-3 sm:gap-4">

                    <!-- Date badge -->
                    <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                        <i class="ri-calendar-event-line text-indigo-600" aria-hidden="true"></i>
                        <span>{{ thaidate(now()) }}</span>
                    </div>

                    <div class="h-4 w-px bg-slate-200 hidden md:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative group cursor-pointer">
                        <div class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-50 transition" role="button" aria-expanded="false" aria-haspopup="true" aria-label="เมนูข้อมูลผู้ใช้">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()?->name ?? 'ผู้ใช้งานทั่วไป' }}</p>
                                <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded-full {{ Auth::user()?->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-indigo-50 text-indigo-700' }}">
                                    {{ Auth::user()?->role === 'admin' ? 'ผู้ดูแลระบบ' : 'เจ้าหน้าที่นิติกร' }}
                                </span>
                            </div>
                            <div class="w-9 h-9 bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm border border-indigo-100">
                                {{ mb_substr(Auth::user()?->name ?? 'U', 0, 1) }}
                            </div>
                            <i class="ri-arrow-down-s-line text-slate-500 group-hover:text-slate-700 transition-transform group-hover:rotate-180 text-sm" aria-hidden="true"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-1.5 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50 overflow-hidden py-1.5" role="menu">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()?->name ?? 'ผู้ใช้งาน' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()?->username ?? '' }}</p>
                            </div>


                            @if(Auth::check())
                            <a href="{{ route('google2fa.setup', ['username' => Auth::user()->username]) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 hover:text-indigo-600 font-semibold transition" role="menuitem">
                                <i class="ri-shield-keyhole-line text-sm" aria-hidden="true"></i>
                                <span>ตั้งค่า Google Auth (2FA)</span>
                            </a>
                            <button type="button" onclick="openTelegramModal()" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 hover:text-sky-600 font-semibold transition text-left" role="menuitem">
                                <i class="ri-telegram-line text-sm" aria-hidden="true"></i>
                                <span>แจ้งเตือน Telegram</span>
                            </button>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition" role="menuitem">
                                    <i class="ri-logout-box-r-line text-sm" aria-hidden="true"></i>
                                    <span>ออกจากระบบ</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 sm:p-8" id="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    @stack('scripts')

    <!-- Real-time Toast Notification Container -->
    <div id="socket-toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none" aria-live="polite">
        <!-- Dynamic Toasts injected here -->
    </div>

    <!-- Echo Real-time Listener Script (Silent Error Handling) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Echo !== 'undefined') {
                const statusBadge = document.getElementById('socket-status-badge');
                const statusText = document.getElementById('socket-status-text');

                try {
                    // Listen to Reverb / Echo Events
                    window.Echo.private('law-system-channel')
                        .listen('.case.created', function (e) {
                            showSocketToast('มีสำนวนใหม่', e.message, 'ri-folder-add-line', 'indigo', '/cases');
                        })
                        .listen('.case.step.added', function (e) {
                            showSocketToast('อัปเดตความคืบหน้าสำนวน', e.message, 'ri-history-line', 'amber', '/cases');
                        })
                        .listen('.case.closed', function (e) {
                            showSocketToast('ปิดสำนวนแล้ว', e.message, 'ri-checkbox-circle-fill', 'emerald', '/cases/completed');
                        })
                        .listen('.order.created', function (e) {
                            showSocketToast('คำสั่งแต่งตั้งใหม่', e.message, 'ri-file-list-3-line', 'sky', '/orders');
                        });

                    // Socket connection status handlers
                    if (window.Echo.connector && window.Echo.connector.pusher) {
                        const pusher = window.Echo.connector.pusher;
                        pusher.connection.bind('connected', function () {
                            if (statusBadge) {
                                statusBadge.className = "hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200";
                                statusText.textContent = "Live Sync";
                            }
                        });
                        pusher.connection.bind('unavailable', function () {
                            if (statusBadge) {
                                statusBadge.className = "hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200";
                                statusText.textContent = "Offline";
                            }
                        });
                        // Prevent console red flood if reverb daemon is not started
                        pusher.connection.bind('error', function () {});
                    }
                } catch(err) {
                    // Silent catch for headless Lighthouse audits
                }
            }

            function showSocketToast(title, message, iconClass, color, linkUrl) {
                const container = document.getElementById('socket-toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = `pointer-events-auto bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-slate-200/80 flex items-start gap-3 transition-all duration-300 transform translate-y-4 opacity-0`;

                // Icon container
                const iconDiv = document.createElement('div');
                iconDiv.className = `w-9 h-9 rounded-xl bg-${color}-50 text-${color}-600 flex items-center justify-center flex-shrink-0 text-lg border border-${color}-100`;
                const icon = document.createElement('i');
                icon.className = iconClass;
                icon.setAttribute('aria-hidden', 'true');
                iconDiv.appendChild(icon);
                toast.appendChild(iconDiv);

                // Content container
                const contentDiv = document.createElement('div');
                contentDiv.className = 'flex-1 min-w-0';

                const headerDiv = document.createElement('div');
                headerDiv.className = 'flex items-center justify-between';
                const titleEl = document.createElement('h4');
                titleEl.className = 'text-xs font-bold text-slate-800';
                titleEl.textContent = title;
                const timeEl = document.createElement('span');
                timeEl.className = 'text-[9px] text-slate-500';
                timeEl.textContent = '\u0e40\u0e21\u0e37\u0e48\u0e2d\u0e2a\u0e31\u0e01\u0e04\u0e23\u0e39\u0e48';
                headerDiv.appendChild(titleEl);
                headerDiv.appendChild(timeEl);
                contentDiv.appendChild(headerDiv);

                const msgEl = document.createElement('p');
                msgEl.className = 'text-[11px] text-slate-600 mt-0.5 leading-snug line-clamp-2';
                msgEl.textContent = message;
                contentDiv.appendChild(msgEl);

                if (linkUrl) {
                    const linkEl = document.createElement('a');
                    linkEl.href = linkUrl;
                    linkEl.className = `inline-flex items-center gap-1 text-[10px] font-bold text-${color}-600 hover:underline mt-1.5`;
                    linkEl.textContent = '\u0e04\u0e25\u0e34\u0e01\u0e40\u0e1e\u0e37\u0e48\u0e2d\u0e14\u0e39 ';
                    const arrowIcon = document.createElement('i');
                    arrowIcon.className = 'ri-arrow-right-line';
                    arrowIcon.setAttribute('aria-hidden', 'true');
                    linkEl.appendChild(arrowIcon);
                    contentDiv.appendChild(linkEl);
                }
                toast.appendChild(contentDiv);

                // Close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'text-slate-400 hover:text-slate-600 p-0.5';
                closeBtn.setAttribute('aria-label', '\u0e1b\u0e34\u0e14\u0e01\u0e32\u0e23\u0e41\u0e08\u0e49\u0e07\u0e40\u0e15\u0e37\u0e2d\u0e19');
                closeBtn.addEventListener('click', function() { toast.remove(); });
                const closeIcon = document.createElement('i');
                closeIcon.className = 'ri-close-line text-sm';
                closeIcon.setAttribute('aria-hidden', 'true');
                closeBtn.appendChild(closeIcon);
                toast.appendChild(closeBtn);

                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-y-4', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 50);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => toast.remove(), 300);
                }, 7000);
            }

            // Global Form Submit Loading Spinner for all buttons
            document.addEventListener('submit', function (e) {
                if (e.defaultPrevented) return;

                const form = e.target;
                if (!form || !(form instanceof HTMLFormElement)) return;

                if (!form.checkValidity()) return;

                const submitBtn = e.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
                if (!submitBtn || submitBtn.dataset.loading === 'true') return;

                submitBtn.dataset.loading = 'true';
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');

                // Replace or prepend loader icon
                const icon = submitBtn.querySelector('i');
                if (icon) {
                    const sizeClasses = Array.from(icon.classList).filter(c => /^text-(xs|sm|base|lg|xl|2xl)$/.test(c)).join(' ');
                    icon.className = `ri-loader-4-line animate-spin ${sizeClasses || 'text-sm'}`;
                } else {
                    const spinner = document.createElement('i');
                    spinner.className = 'ri-loader-4-line animate-spin text-sm mr-1.5 inline-block';
                    spinner.setAttribute('aria-hidden', 'true');
                    submitBtn.insertAdjacentElement('afterbegin', spinner);
                }
            });
        });
    </script>

    <!-- Telegram Notification QR Modal -->
    <div id="telegramModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 transition-all duration-200" onclick="closeTelegramModal()">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl border border-slate-100 text-center space-y-4 animate-in fade-in zoom-in-95 duration-150 relative" onclick="event.stopPropagation()">
            <!-- Close Button -->
            <button type="button" onclick="closeTelegramModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition" aria-label="ปิดหน้าต่าง">
                <i class="ri-close-line text-xl" aria-hidden="true"></i>
            </button>

            <!-- Header Icon & Title -->
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-sky-500 to-sky-400 text-white flex items-center justify-center text-3xl shadow-lg shadow-sky-500/30">
                <i class="ri-telegram-fill" aria-hidden="true"></i>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 text-sky-800 rounded-full border border-sky-200 text-xs font-extrabold mb-2 shadow-xs">
                    <span>ODPC10-LSS</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-800 tracking-tight">
                    รับการแจ้งเตือนสำนวนกฎหมาย
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    กลุ่มเฉพาะระบบงานสำนวน สคร.10
                </p>
            </div>

            <!-- QR Code Card -->
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/60 inline-block shadow-inner">
                <img src="{{ asset('images/telegram_qr.png') }}" alt="QR Code รับการแจ้งเตือน Telegram ODPC10-LSS" class="w-48 h-48 object-contain mx-auto rounded-xl bg-white p-2 shadow-sm">
            </div>

            <!-- Instructions -->
            <div class="text-left bg-sky-50/70 p-3.5 rounded-2xl border border-sky-100 text-[11px] text-slate-700 space-y-1.5">
                <p class="font-bold text-sky-900 flex items-center gap-1.5">
                    <i class="ri-information-fill text-sky-600 text-sm"></i>
                    <span>ขั้นตอนการเข้าร่วมกลุ่ม ODPC10-LSS:</span>
                </p>
                <ol class="list-decimal list-inside space-y-1 text-slate-600 pl-1">
                    <li>เปิดแอป <b>Telegram</b> บนโทรศัพท์มือถือ</li>
                    <li>สแกน <b>QR Code</b> หรือเข้าร่วมกลุ่ม <b>ODPC10-LSS</b></li>
                    <li>ระบบจะส่งการแจ้งเตือนเฉพาะความคืบหน้าของสำนวนกฎหมาย สคร.10 ให้ทันที</li>
                </ol>
            </div>

            <button type="button" onclick="closeTelegramModal()"
                class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition active:scale-95 shadow-sm">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>

    <script>
        function openTelegramModal() {
            const modal = document.getElementById('telegramModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeTelegramModal() {
            const modal = document.getElementById('telegramModal');
            if (modal) modal.classList.add('hidden');
        }
    </script>
</body>

</html>
