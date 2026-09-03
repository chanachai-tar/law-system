<!DOCTYPE html>
<html lang="th" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="เข้าสู่ระบบงานสำนวนกฎหมาย ODPC10 LSS สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี">
    <meta name="theme-color" content="#0f172a">

    <title>{{ config('app.name', 'เข้าสู่ระบบ - ระบบงานสำนวนกฎหมาย') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/lss_logo_rounded.webp') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/lss_logo_rounded.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/lss_logo_rounded.webp') }}">

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
        
        /* OTP Input hide spinner */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <main class="max-w-md w-full glass-effect rounded-3xl shadow-2xl overflow-hidden border border-white/20 fade-in-up">

        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 p-6 text-center relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-48 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center justify-center gap-3.5 px-4 py-2 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 mb-2 shadow-inner">
                    <img src="{{ asset('images/odpc10.webp') }}" alt="ตรา สคร.10 อุบลราชธานี" class="h-9 w-auto object-contain drop-shadow">
                    <div class="h-6 w-px bg-white/20"></div>
                    <img src="{{ asset('images/lss_logo_rounded.webp') }}" alt="โลโก้ ODPC10-LSS" class="h-9 w-9 object-contain drop-shadow">
                </div>

                <h1 class="text-white text-xl sm:text-2xl font-extrabold tracking-wide">
                    ระบบงานสำนวนกฎหมาย
                </h1>
                <p class="text-indigo-200 text-xs mt-1 font-medium">
                    ODPC10 LSS • สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี
                </p>
            </div>
        </div>

        <!-- Login Options -->
        <div id="loginOptionsContainer" class="p-6 pt-5 {{ $errors->any() ? 'hidden' : '' }}">
            <div class="text-center mb-4">
                <h2 class="text-sm font-bold text-slate-800">ยินดีต้อนรับกลับมา</h2>
                <p class="text-xs text-slate-500 mt-1.5">กรุณาเลือกช่องทางการเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('sso.login') }}" id="ssoBtn" onclick="handleSsoClick(event, this)"
                    class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all duration-200 flex items-center justify-center gap-2.5 active:scale-95 group">
                    <i id="ssoBtnIcon" class="ri-fingerprint-fill text-lg group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    <span id="ssoBtnText">เข้าสู่ระบบด้วย SSO ODPC</span>
                </a>

                <button type="button" onclick="toggleAuthForm(true)"
                    class="w-full py-3 px-4 bg-white hover:bg-slate-50 text-emerald-700 border-2 border-emerald-600 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2.5 shadow-sm hover:shadow active:scale-95 group">
                    <i class="ri-qr-code-line text-lg group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    <span>เข้าสู่ระบบด้วย Google Authenticator</span>
                </button>

                <button type="button" onclick="togglePasswordForm(true)"
                    class="w-full py-3 px-4 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2.5 shadow-sm hover:shadow active:scale-95 group">
                    <i class="ri-lock-password-line text-lg group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    <span>เข้าใช้งานด้วยรหัสผ่าน</span>
                </button>
            </div>
        </div>

        <!-- Authenticator Form Section -->
        <div id="authenticatorFormContainer" class="p-6 pt-4 {{ $errors->has('auth_error') ? '' : 'hidden' }}">
            <div class="flex items-center gap-3 mb-4">
                <button type="button" onclick="toggleAuthForm(false)" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors shrink-0" title="ย้อนกลับ">
                    <i class="ri-arrow-left-s-line text-xl"></i>
                </button>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">เข้าสู่ระบบด้วย Google Authenticator</h2>
                </div>
            </div>

            <div id="otpInputSection">
            <!-- OTP Login Form -->
            <form method="POST" action="{{ route('google2fa.verify') }}" class="space-y-4" onsubmit="updateHiddenOtp(); setLoadingState('otpBtn', 'otpBtnIcon', 'otpBtnText');">
                @csrf
                
                @if($errors->has('auth_error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-2.5 rounded-xl flex items-start gap-2 shadow-sm text-[11px]" role="alert">
                        <i class="ri-error-warning-fill text-sm mt-0.5" aria-hidden="true"></i>
                        <div>{{ $errors->first('auth_error') }}</div>
                    </div>
                @endif
                


                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label class="text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                            รหัส 6 หลัก (OTP Code)
                        </label>
                    </div>
                    
                    <!-- 6 Boxes Input -->
                    <div class="flex justify-between gap-2">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        <input type="number" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-bold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                    
                    <input type="hidden" name="totp_code" id="actual_totp_code" required>
                    
                    <!-- TOTP Countdown Timer -->
                    <div class="mt-3 flex flex-col items-center justify-center">
                        <div class="text-[10px] text-slate-500 mb-1.5">
                            รหัสจะเปลี่ยนใหม่ในอีก <span id="totpCountdownText" class="font-bold text-indigo-600 text-[11px]">30</span> วินาที
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1 max-w-[200px] overflow-hidden">
                            <div id="totpCountdownBar" class="bg-indigo-600 h-1 rounded-full transition-all duration-1000 ease-linear" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="otpBtn"
                    class="w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                    <i id="otpBtnIcon" class="ri-check-line text-sm"></i>
                    <span id="otpBtnText">เข้าสู่ระบบ</span>
                </button>
            </form>

            </div> <!-- End otpInputSection -->

                        <!-- Hidden QR Setup Section -->
            <div id="qrSetupSection" class="hidden mt-3 pt-3 border-t border-slate-100 text-center">
                <h3 class="text-xs font-bold text-slate-800 mb-1">สแกนเพื่อลงทะเบียน</h3>
                <p class="text-[10px] text-slate-500 mb-2 leading-relaxed">
                    ใช้แอป Google Authenticator สแกน QR Code ด้านล่างนี้<br>
                    เมื่อสแกนเสร็จแล้ว ให้กดย้อนกลับไปกรอกรหัส 6 หลัก
                </p>
                
                <div class="inline-block p-2 bg-white rounded-2xl border-2 border-emerald-100 shadow-sm relative min-h-[140px] min-w-[140px] flex items-center justify-center mb-2">
                    <div id="qrLoading" class="flex flex-col items-center justify-center text-emerald-600">
                        <i class="ri-loader-4-line animate-spin text-3xl mb-2"></i>
                        <span class="text-[10px] font-bold">กำลังสร้าง QR Code...</span>
                    </div>
                    <img id="qrCodeImage" src="" alt="Google Authenticator QR" class="hidden w-32 h-32 rounded-lg">
                </div>

                <hr class="my-3 border-slate-200">
                <p class="text-[10px] text-slate-500 mb-2 mt-3 leading-relaxed">
                    ดาวน์โหลดแอป Google Authenticator สำหรับมือถือของคุณ<br>
                    สแกน QR Code หรือค้นหาแอปด้วยคำว่า "Google Authenticator"
                </p>

                <div class="flex items-center justify-center gap-6">
                    <div class="flex flex-col items-center">
                        <div class="p-1 bg-white border border-slate-200 rounded-lg shadow-sm mb-1">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fapps.apple.com%2Fth%2Fapp%2Fgoogle-authenticator%2Fid388497605" alt="iOS App Store" class="w-10 h-10 rounded opacity-90">
                        </div>
                        <span class="text-[8px] font-bold text-slate-500">App Store</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="p-1 bg-white border border-slate-200 rounded-lg shadow-sm mb-1">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.google.android.apps.authenticator2" alt="Google Play" class="w-10 h-10 rounded opacity-90">
                        </div>
                        <span class="text-[8px] font-bold text-slate-500">Google Play</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="button" onclick="hideQrRegistration()"
                        class="w-full py-3 px-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                        <i class="ri-arrow-left-s-line text-sm"></i>
                        <span>สแกนเสร็จแล้ว กลับไปกรอกรหัส</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Standard Password Form Section -->
        <div id="passwordFormContainer" class="p-6 pt-4 {{ ($errors->any() && !$errors->has('auth_error')) ? '' : 'hidden' }}">
            <div class="flex items-center gap-3 mb-4">
                <button type="button" onclick="togglePasswordForm(false)" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors shrink-0" title="ย้อนกลับ">
                    <i class="ri-arrow-left-s-line text-xl"></i>
                </button>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">เข้าใช้งานด้วยรหัสผ่าน</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4" onsubmit="setLoadingState('loginBtn', 'loginBtnIcon', 'loginBtnText')">
                @csrf
                @if($errors->any() && !$errors->has('auth_error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3.5 rounded-xl flex items-start gap-2.5 shadow-sm text-xs" role="alert">
                        <i class="ri-error-warning-fill text-base mt-0.5" aria-hidden="true"></i>
                        <div>
                            <p class="font-bold">เข้าสู่ระบบไม่สำเร็จ</p>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif
                <div class="space-y-1">
                    <label for="username_input" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">ชื่อผู้ใช้งาน (Username)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-user-3-line" aria-hidden="true"></i>
                        </div>
                        <input type="text" name="username" id="username_input" placeholder="ระบุชื่อผู้ใช้งาน" required {{ $errors->any() ? 'autofocus' : '' }}
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="password_input" class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1">รหัสผ่าน (Password)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                            <i class="ri-lock-password-line" aria-hidden="true"></i>
                        </div>
                        <input type="password" name="password" id="password_input" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    </div>
                </div>
                <button type="submit" id="loginBtn"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg transition-all duration-200 flex items-center justify-center gap-2 active:scale-95">
                    <i id="loginBtnIcon" class="hidden" aria-hidden="true"></i>
                    <span>เข้าสู่ระบบ</span>
                </button>
            </form>
        </div>

        <div class="bg-slate-50 p-4 border-t border-slate-100 text-center">
            <p class="text-[11px] text-slate-500">สำหรับเจ้าหน้าที่และผู้ได้รับอนุญาตเท่านั้น</p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateTotpCountdown();

            const hasAuthError = document.getElementById('authenticatorFormContainer').classList.contains('hidden') === false;
            const hasLoginError = document.getElementById('passwordFormContainer').classList.contains('hidden') === false;
            if (hasAuthError || hasLoginError) {
                document.getElementById('loginOptionsContainer').classList.add('hidden');
            }

            // OTP Inputs logic
            const otpInputs = document.querySelectorAll('.otp-input');
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
                
                // Allow pasting a 6-digit code
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
        });

        function updateHiddenOtp() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const code = Array.from(otpInputs).map(i => i.value).join('');
            document.getElementById('actual_totp_code').value = code;
        }

        function toggleAuthForm(show) {
            const options = document.getElementById('loginOptionsContainer');
            const authForm = document.getElementById('authenticatorFormContainer');
            const pwdForm = document.getElementById('passwordFormContainer');
            
            if (show) {
                options.classList.add('hidden');
                pwdForm.classList.add('hidden');
                authForm.classList.remove('hidden');
            } else {
                authForm.classList.add('hidden');
                options.classList.remove('hidden');
                // reset inner state
                if (document.getElementById('otpInputSection')) {
                    document.getElementById('otpInputSection').classList.remove('hidden');
                    if (document.getElementById('qrSetupSection')) {
                        document.getElementById('qrSetupSection').classList.add('hidden');
                    }
                }
            }
        }

        

        

                function setLoadingState(btnId, iconId, textId) {
            const btn = document.getElementById(btnId);
            const icon = document.getElementById(iconId);
            const text = document.getElementById(textId);
            
            if (btn) {
                btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            }
            if (icon) {
                icon.className = 'ri-loader-4-line animate-spin text-lg';
            }
            if (text) {
                text.textContent = 'กำลังเข้าสู่ระบบ...';
            }
        }

        function handleSsoClick(event, btn) {
            event.preventDefault();
            const icon = document.getElementById('ssoBtnIcon');
            const text = document.getElementById('ssoBtnText');
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
            
            if (icon) icon.className = 'ri-loader-4-line animate-spin text-lg group-hover:scale-110 transition-transform';
            if (text) text.textContent = 'กำลังเข้าสู่ระบบ...';
            
            window.location.href = btn.href;
        }

        function togglePasswordForm(show) {
            const options = document.getElementById('loginOptionsContainer');
            const form = document.getElementById('passwordFormContainer');
            const authForm = document.getElementById('authenticatorFormContainer');
            const usernameInput = document.getElementById('username_input');
            
            if (show) {
                options.classList.add('hidden');
                authForm.classList.add('hidden');
                form.classList.remove('hidden');
                setTimeout(() => { usernameInput.focus(); }, 100);
            } else {
                form.classList.add('hidden');
                options.classList.remove('hidden');
            }
        }


    
        function updateTotpCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const remaining = 30 - (now % 30);
            
            const textEl = document.getElementById('totpCountdownText');
            const barEl = document.getElementById('totpCountdownBar');
            
            if (textEl && barEl) {
                textEl.textContent = remaining;
                
                const percentage = (remaining / 30) * 100;
                barEl.style.width = percentage + '%';
                
                if (remaining <= 5) {
                    barEl.className = 'h-1 rounded-full transition-all duration-1000 ease-linear bg-rose-500';
                    textEl.className = 'font-bold text-[11px] text-rose-600';
                } else if (remaining <= 15) {
                    barEl.className = 'h-1 rounded-full transition-all duration-1000 ease-linear bg-amber-500';
                    textEl.className = 'font-bold text-[11px] text-amber-500';
                } else {
                    barEl.className = 'h-1 rounded-full transition-all duration-1000 ease-linear bg-indigo-600';
                    textEl.className = 'font-bold text-[11px] text-indigo-600';
                }
            }
        }
        
        setInterval(updateTotpCountdown, 1000);
    </script>
</body>
</html>
