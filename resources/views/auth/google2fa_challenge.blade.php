<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ยืนยันตัวตนสองขั้นตอน - ระบบงานสำนวนกฎหมาย</title>
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
    <main class="max-w-md w-full glass-effect rounded-3xl shadow-2xl overflow-hidden border border-white/20 fade-in-up">
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 p-6 text-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center gap-3.5 px-4 py-2 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 mb-2 shadow-inner">
                    <img src="{{ asset('images/odpc10.webp') }}" alt="ตรา สคร.10 อุบลราชธานี" class="h-9 w-auto object-contain drop-shadow">
                    <div class="h-6 w-px bg-white/20"></div>
                    <img src="{{ asset('images/lss_logo_rounded.webp') }}" alt="โลโก้ ODPC10-LSS" class="h-9 w-9 object-contain drop-shadow">
                </div>
                <h1 class="text-white text-xl sm:text-2xl font-extrabold tracking-wide">
                    ยืนยันตัวตนสองขั้นตอน
                </h1>
                <p class="text-indigo-200 text-xs mt-1 font-medium">
                    กรุณากรอกรหัสจากแอป Google Authenticator
                </p>
            </div>
        </div>

        <div class="p-6 pt-4">
            <form method="POST" action="{{ route('google2fa.verify') }}" class="space-y-4">
                @csrf
                @if($errors->has('auth_error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-2.5 rounded-xl flex items-start gap-2 shadow-sm text-[11px]" role="alert">
                        <i class="ri-error-warning-fill text-sm mt-0.5" aria-hidden="true"></i>
                        <div>{{ $errors->first('auth_error') }}</div>
                    </div>
                @endif
                <div class="space-y-1">
                    <label for="totp_code" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">รหัส 6 หลัก (OTP Code)</label>
                    <div class="relative group">
                        <input type="text" name="totp_code" id="totp_code" required autofocus maxlength="6"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-center text-xl font-bold tracking-widest focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                    <i class="ri-check-line text-sm"></i>
                    <span>ยืนยัน</span>
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center justify-center gap-1">
                    <i class="ri-arrow-left-line"></i> กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </div>
    </main>
</body>
</html>
