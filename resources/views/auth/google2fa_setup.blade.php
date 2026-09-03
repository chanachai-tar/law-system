<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ตั้งค่า Google Authenticator</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Prompt', sans-serif; background-color: #0f172a; }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 text-center">
        <h2 class="text-xl font-bold text-slate-800 mb-2">ลงทะเบียน Google Authenticator</h2>
        <p class="text-sm text-slate-500 mb-2">สำหรับผู้ใช้งาน: <span class="font-bold text-indigo-600">{{ $user->name }}</span></p>
        
        <div class="inline-block p-4 border-2 border-indigo-100 rounded-2xl mb-3">
            <img src="{{ $googleChartsUrl }}" alt="QR Code" class="w-48 h-48 mx-auto">
        </div>
        
<hr class="border-slate-200 mb-2">
        <p class="text-sm text-slate-600 mb-2">ดาวน์โหลดแอป Google Authenticator บนมือถือของคุณ:</p>

                <div class="flex items-center justify-center gap-6 mb-6">
            <div class="flex flex-col items-center">
                <div class="p-1.5 bg-white border border-slate-200 rounded-xl shadow-sm mb-1.5">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fapps.apple.com%2Fth%2Fapp%2Fgoogle-authenticator%2Fid388497605" alt="iOS App Store" class="w-12 h-12 rounded opacity-90">
                </div>
                <span class="text-[10px] font-bold text-slate-500">App Store</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="p-1.5 bg-white border border-slate-200 rounded-xl shadow-sm mb-1.5">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.google.android.apps.authenticator2" alt="Google Play" class="w-12 h-12 rounded opacity-90">
                </div>
                <span class="text-[10px] font-bold text-slate-500">Google Play</span>
            </div>
        </div>
        
        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
            1. เปิดแอป Google Authenticator บนมือถือของคุณ<br>
            2. กดปุ่ม + และเลือก "Scan a QR code"<br>
            3. สแกน QR Code ด้านบนนี้<br>
            4. หลังจากแสกนเสร็จแล้ว ให้กลับไปหน้าล็อกอินและกรอกเลข 6 หลัก
        </p>

        <form method="POST" action="{{ route('google2fa.setup.confirm') }}" class="mt-4">
            @csrf
            
            @if($errors->has('totp_code'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 p-2.5 rounded-xl flex items-start gap-2 shadow-sm text-xs mb-4 text-left">
                    <i class="ri-error-warning-fill text-sm mt-0.5"></i>
                    <div>{{ $errors->first('totp_code') }}</div>
                </div>
            @endif

                        <div class="space-y-2 mb-5 text-left">
                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider ml-1 text-center block">กรอกรหัส 6 หลักเพื่อยืนยัน</label>
                <div class="flex items-center gap-2 justify-center" dir="ltr">
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required autofocus>
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required>
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required>
                    <div class="w-2 h-0.5 bg-slate-200 rounded-full mx-0.5"></div>
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required>
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required>
                    <input type="text" maxlength="1" inputmode="numeric" autocomplete="one-time-code" class="setup-otp-input w-10 h-12 text-center text-xl font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all shadow-sm" required>
                </div>
                <input type="hidden" name="totp_code" id="actual_setup_totp_code">
            </div>

            <button type="submit" class="inline-block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg transition-all mb-4">
                ยืนยันและเปิดใช้งาน 2FA
            </button>
        </form>

        <a href="{{ url('cases') }}" class="inline-block text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="ri-arrow-left-line align-middle mr-1"></i> ย้อนกลับ (ทำภายหลัง)
        </a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const otpInputs = document.querySelectorAll('.setup-otp-input');
            
            function updateHiddenOtp() {
                const code = Array.from(otpInputs).map(i => i.value).join('');
                document.getElementById('actual_setup_totp_code').value = code;
            }

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
            
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    updateHiddenOtp();
                });
            }
        });
    </script>
</body>
</html>
