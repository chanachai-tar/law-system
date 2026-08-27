<!DOCTYPE html>
<html lang="th" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="เข้าสู่ระบบงานสำนวนกฎหมาย ODPC10 LSS สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี">
    <meta name="theme-color" content="#0f172a">

    <title>{{ config('app.name', 'เข้าสู่ระบบ - ระบบงานสำนวนกฎหมาย') }}</title>

    <!-- Favicon & App Icons (Rounded Corners) -->
    <link rel="icon" type="image/png" href="{{ asset('images/lss_logo_rounded.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/lss_logo_rounded.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/lss_logo_rounded.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Prompt', 'Noto Sans Thai', sans-serif;
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.13) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(14, 165, 233, 0.1) 0px, transparent 50%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
        }
        .fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Main Container -->
    <main class="max-w-md w-full glass-effect rounded-3xl shadow-2xl overflow-hidden border border-white/20 fade-in-up">

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-48 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Logos Badge -->
                <div class="inline-flex items-center justify-center gap-3.5 px-4 py-2 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 mb-3.5 shadow-inner">
                    <img src="{{ asset('images/odpc10.png') }}" alt="ตรา สคร.10 อุบลราชธานี" class="h-9 w-auto object-contain drop-shadow">
                    <div class="h-6 w-px bg-white/20"></div>
                    <img src="{{ asset('images/lss_logo_rounded.png') }}" alt="โลโก้ ODPC10-LSS" class="h-9 w-9 object-contain drop-shadow">
                </div>

                <h1 class="text-white text-xl sm:text-2xl font-extrabold tracking-wide">
                    ระบบงานสำนวนกฎหมาย
                </h1>
                <p class="text-indigo-200 text-xs mt-1 font-medium">
                    ODPC10 LSS • สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี
                </p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="p-8 pt-8">
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3.5 rounded-xl flex items-start gap-2.5 shadow-sm text-xs" role="alert">
                        <i class="ri-error-warning-fill text-base mt-0.5" aria-hidden="true"></i>
                        <div>
                            <p class="font-bold">เข้าสู่ระบบไม่สำเร็จ</p>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Username Input -->
                <div class="space-y-1">
                    <label for="username_input" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">
                        ชื่อผู้ใช้งาน (Username)
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-user-3-line" aria-hidden="true"></i>
                        </div>
                        <input type="text" name="username" id="username_input" placeholder="ระบุชื่อผู้ใช้งาน" required autofocus
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1">
                    <label for="password_input" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">
                        รหัสผ่าน (Password)
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-lock-password-line" aria-hidden="true"></i>
                        </div>
                        <input type="password" name="password" id="password_input" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="loginBtn"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                    <i id="loginBtnIcon" class="ri-login-box-line text-sm" aria-hidden="true"></i>
                    <span id="loginBtnText">เข้าสู่ระบบ</span>
                </button>

                <!-- Divider -->
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-3 bg-white text-slate-500 font-bold uppercase text-[10px] tracking-widest">หรือเข้าใช้งานด้วย</span>
                    </div>
                </div>

                <!-- SSO Button (ODPC10 IDP) -->
                <div>
                    <a href="{{ route('sso.login') }}" id="ssoBtn" onclick="handleSsoClick(this)"
                        class="w-full py-3 px-4 bg-slate-50 hover:bg-white text-slate-700 hover:text-indigo-600 border border-slate-200 hover:border-indigo-300 rounded-xl text-xs font-bold transition-all duration-200 flex items-center justify-center gap-2.5 shadow-sm hover:shadow active:scale-95 group">
                        <i id="ssoBtnIcon" class="ri-shield-user-line text-base text-indigo-600 group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        <span id="ssoBtnText">เข้าสู่ระบบด้วย SSO ODPC IDP</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer Notice -->
        <div class="bg-slate-50 p-4 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-500">
                สำหรับเจ้าหน้าที่และผู้ได้รับอนุญาตเท่านั้น
            </p>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnIcon = document.getElementById('loginBtnIcon');
            const loginBtnText = document.getElementById('loginBtnText');

            if (form && loginBtn) {
                form.addEventListener('submit', function () {
                    if (form.checkValidity()) {
                        loginBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                        if (loginBtnIcon) loginBtnIcon.className = 'ri-loader-4-line animate-spin text-sm';
                        if (loginBtnText) loginBtnText.textContent = 'กำลังเข้าสู่ระบบ...';
                    }
                });
            }
        });

        function handleSsoClick(btn) {
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            const icon = document.getElementById('ssoBtnIcon');
            const text = document.getElementById('ssoBtnText');
            if (icon) icon.className = 'ri-loader-4-line animate-spin text-sm text-indigo-600';
            if (text) text.textContent = 'กำลังเชื่อมต่อไปยัง ODPC IDP...';
        }
    </script>

</body>

</html>
