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
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
                    กรุณากรอกรหัส 6 หลักจากแอป Google Authenticator
                </p>
            </div>
        </div>

        <div class="p-6 pt-4">
            <form method="POST" action="{{ route('google2fa.verify') }}" class="space-y-6" id="otpForm">
                @csrf
                @if($errors->has('auth_error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-2.5 rounded-xl flex items-start gap-2 shadow-sm text-[11px]" role="alert">
                        <i class="ri-error-warning-fill text-sm mt-0.5" aria-hidden="true"></i>
                        <div>{{ $errors->first('auth_error') }}</div>
                    </div>
                @endif
                
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1 text-center block">รหัส 6 หลัก (OTP Code)</label>
                    
                    <!-- 6 Boxes Input -->
                    <div class="flex justify-between gap-2 max-w-xs mx-auto">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    
                    <input type="hidden" name="totp_code" id="actual_totp_code" required>
                </div>
                
                <button type="submit" id="submitBtn"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                    <i class="ri-check-line text-sm" id="submitIcon"></i>
                    <span id="submitText">ยืนยันรหัส</span>
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center justify-center gap-1">
                    <i class="ri-arrow-left-line"></i> กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('actual_totp_code');
            const form = document.getElementById('otpForm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Auto-focus first input
            setTimeout(() => { if (otpInputs.length) otpInputs[0].focus(); }, 100);

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (this.value.length > 1) {
                        this.value = this.value.slice(0,1);
                    }
                    if (this.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    updateHiddenOtp();
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').substring(0, 6);
                    for (let i = 0; i < pastedData.length; i++) {
                        if (otpInputs[index + i]) {
                            otpInputs[index + i].value = pastedData[i];
                        }
                    }
                    if (pastedData.length > 0) {
                        const nextIndex = Math.min(index + pastedData.length, 5);
                        otpInputs[nextIndex].focus();
                    }
                    updateHiddenOtp();
                });
            });

            function updateHiddenOtp() {
                hiddenInput.value = Array.from(otpInputs).map(i => i.value).join('');
                if (hiddenInput.value.length === 6) {
                    // Auto submit when 6 digits are entered
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                    document.getElementById('submitIcon').className = 'ri-loader-4-line animate-spin text-sm';
                    document.getElementById('submitText').textContent = 'กำลังตรวจสอบ...';
                    form.submit();
                }
            }
        });
    </script>
</body>
</html>
