@extends('layouts.app')

@section('header_title')
    <i class="ri-telegram-fill text-sky-500 text-lg" aria-hidden="true"></i>
    <span>ตั้งค่าการแจ้งเตือน Telegram (ODPC10-LSS)</span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-fill text-emerald-600 text-base" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1" aria-label="ปิดแจ้งเตือน">
                <i class="ri-close-line text-sm" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1 shadow-xs">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5">
                    <i class="ri-error-warning-fill text-rose-500 text-sm" aria-hidden="true"></i>
                    <span>{{ $error }}</span>
                </p>
            @endforeach
        </div>
    @endif

    <!-- 1. Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-sky-950/20 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-sky-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -top-10 w-48 h-48 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-sky-200 text-xs font-medium border border-white/10 shadow-xs">
                    <i class="ri-telegram-fill text-sky-400" aria-hidden="true"></i>
                    <span>Telegram Real-Time Notification Engine</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    ตั้งค่าการแจ้งเตือน Telegram
                </h2>
                <p class="text-sky-200 text-xs sm:text-sm max-w-xl font-light leading-relaxed">
                    ส่งข้อความแจ้งเตือนอัตโนมัติเข้ากลุ่ม <b>ODPC10-LSS</b> ทันทีเมื่อมีการเปิดสำนวน, บันทึกความคืบหน้า (ครั้งที่...), และปิดสำนวนคดี
                </p>
            </div>

            <!-- Bot Status Badge -->
            <div class="flex-shrink-0 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/15 text-center min-w-[180px]">
                <p class="text-[10px] uppercase font-bold tracking-wider text-sky-200">สถานะการเชื่อมต่อบอท</p>
                <div class="mt-2 flex items-center justify-center gap-2">
                    @if($isConnected)
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse" aria-hidden="true"></span>
                        <span class="text-xs font-extrabold text-emerald-300">เชื่อมต่อแล้ว (พร้อมใช้งาน)</span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400" aria-hidden="true"></span>
                        <span class="text-xs font-bold text-amber-200">ยังไม่เชื่อมต่อ / รอตั้งค่า</span>
                    @endif
                </div>
                @if($botInfo)
                    <p class="text-[10px] text-sky-300 mt-1 font-mono">@ {{ $botInfo['username'] ?? 'Bot' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Form Settings (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('settings.telegram.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/70 shadow-sm space-y-6">
                @csrf

                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-extrabold text-sm text-slate-800 flex items-center gap-2">
                        <i class="ri-settings-4-line text-indigo-600 text-base" aria-hidden="true"></i>
                        <span>การเชื่อมต่อ Telegram Bot & Group</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">ระบุ Token ของบอทและ Chat ID ของกลุ่มเฉพาะระบบงานสำนวน</p>
                </div>

                <!-- Bot Token Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="telegram_bot_token" class="text-xs font-bold text-slate-700">
                            Telegram Bot Token <span class="text-rose-500">*</span>
                        </label>
                        <a href="https://t.me/BotFather" target="_blank" rel="noopener" class="text-[11px] text-sky-600 hover:underline flex items-center gap-1 font-bold">
                            <i class="ri-external-link-line" aria-hidden="true"></i>
                            <span>สร้างบอทผ่าน @BotFather</span>
                        </a>
                    </div>
                    <div class="relative flex items-center">
                        <i class="ri-key-2-line absolute left-3.5 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                        <input type="password" name="telegram_bot_token" id="telegram_bot_token" value="{{ old('telegram_bot_token', $botToken) }}"
                            placeholder="เช่น 1234567890:ABCdefGhIJKlmNoPQRsTUVwxyZ..."
                            class="w-full h-11 pl-9 pr-10 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-mono text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                        <button type="button" onclick="togglePasswordVisibility('telegram_bot_token', this)"
                            class="absolute right-3 text-slate-400 hover:text-slate-600 p-1" aria-label="แสดง/ซ่อน Token">
                            <i class="ri-eye-line text-sm" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">ได้รับจากบอท @BotFather เมื่อสร้าง Telegram Bot ใหม่</p>
                </div>

                <!-- Chat ID & Group Name Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="telegram_chat_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Telegram Chat ID / Group ID <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <i class="ri-hashtag absolute left-3.5 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                            <input type="text" name="telegram_chat_id" id="telegram_chat_id" value="{{ old('telegram_chat_id', $chatId) }}"
                                placeholder="เช่น -100123456789 หรือ ID ผู้ใช้"
                                class="w-full h-11 pl-9 pr-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-mono text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">ID ของกลุ่ม หรือแชนเนล (เช็กได้จาก @userinfobot หรือ Forward ข้อความ)</p>
                    </div>

                    <div>
                        <label for="telegram_group_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            ชื่อกลุ่มแสดงในหัวข้อ <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <i class="ri-group-line absolute left-3.5 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                            <input type="text" name="telegram_group_name" id="telegram_group_name" required value="{{ old('telegram_group_name', $groupName) }}"
                                placeholder="ODPC10-LSS"
                                class="w-full h-11 pl-9 pr-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">ชื่อที่แสดงในหัวข้อข้อความแจ้งเตือน (แนะนำ: ODPC10-LSS)</p>
                    </div>
                </div>

                <!-- Event Notification Toggles -->
                <div class="border-t border-slate-100 pt-5 space-y-3">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">
                        เลือกเหตุการณ์ที่ต้องการให้ส่งการแจ้งเตือน
                    </h4>

                    <div class="space-y-2.5">
                        <label class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 cursor-pointer transition">
                            <input type="checkbox" name="notify_case_created" value="1" {{ $notifyCaseCreated ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 rounded-md focus:ring-indigo-500 border-slate-300">
                            <div>
                                <p class="text-xs font-bold text-slate-800">1. เมื่อมีการเปิดสำนวนคดีใหม่</p>
                                <p class="text-[11px] text-slate-500">แจ้งเตือนเลขที่สำนวน, ประเภท (ตส./สล./สว.), ชื่อเรื่อง และวันครบกำหนดรายงาน</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 cursor-pointer transition">
                            <input type="checkbox" name="notify_step_added" value="1" {{ $notifyStepAdded ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 rounded-md focus:ring-indigo-500 border-slate-300">
                            <div>
                                <p class="text-xs font-bold text-slate-800">2. เมื่อมีการบันทึกขั้นตอน / บันทึกความคืบหน้า (ครั้งที่...)</p>
                                <p class="text-[11px] text-slate-500">แจ้งเตือนรายละเอียดการดำเนินการล่าสุด พร้อมวันที่บันทึก</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 cursor-pointer transition">
                            <input type="checkbox" name="notify_case_closed" value="1" {{ $notifyCaseClosed ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 rounded-md focus:ring-indigo-500 border-slate-300">
                            <div>
                                <p class="text-xs font-bold text-slate-800">3. เมื่อปิดสำนวนคดีแล้วเสร็จ</p>
                                <p class="text-[11px] text-slate-500">แจ้งเตือนผลการวินิจฉัย/บทลงโทษ และยอดเงินชดใช้ความเสียหาย</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Custom QR Code Upload -->
                <div class="border-t border-slate-100 pt-5 space-y-3">
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">
                        อัปโหลดรูป QR Code กลุ่ม Telegram ใหม่
                    </h4>
                    <div>
                        <input type="file" name="telegram_qr_image" id="telegram_qr_image" accept="image/*"
                            class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 file:cursor-pointer cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">อัปโหลดไฟล์รูปภาพ QR Code (PNG, JPG) ของกลุ่ม ODPC10-LSS เพื่อแสดงในหน้าต่างสแกนรับแจ้งเตือน</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="submit"
                        class="px-7 py-3 rounded-2xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition active:scale-95 flex items-center gap-2">
                        <i class="ri-save-line text-base" aria-hidden="true"></i>
                        <span>บันทึกการตั้งค่า Telegram</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Live QR Preview & One-click Test (1 Col) -->
        <div class="space-y-6">

            <!-- Current QR Code Preview Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-sm text-center space-y-4">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 text-sky-800 rounded-full border border-sky-200 text-xs font-extrabold shadow-xs">
                    <i class="ri-group-line text-sky-600"></i>
                    <span>กลุ่ม: {{ $groupName }}</span>
                </div>

                <h3 class="font-extrabold text-sm text-slate-800">
                    QR Code เข้าร่วมกลุ่มปัจจุบัน
                </h3>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/60 inline-block shadow-inner">
                    <img src="{{ asset('images/telegram_qr.png') }}?v={{ time() }}" alt="QR Code กลุ่ม ODPC10-LSS"
                        class="w-44 h-44 object-contain mx-auto rounded-xl bg-white p-2 shadow-sm">
                </div>

                <p class="text-[11px] text-slate-500">
                    ภาพนี้จะถูกแสดงในหน้าต่างป๊อปอัปเมื่อเจ้าหน้าที่กดปุ่ม "แจ้งเตือน Telegram"
                </p>
            </div>

            <!-- One-Click Test Notification Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-sm space-y-4">
                <h3 class="font-extrabold text-sm text-slate-800 flex items-center gap-2">
                    <i class="ri-notification-badge-line text-sky-600 text-base" aria-hidden="true"></i>
                    <span>ทดสอบส่งข้อความแจ้งเตือน</span>
                </h3>
                <p class="text-xs text-slate-500">
                    กดปุ่มด้านล่างเพื่อทดสอบส่งข้อความ Ping ไปยังกลุ่ม Telegram ทันที เพื่อตรวจสอบว่าบอททำงานถูกต้อง
                </p>

                <button type="button" onclick="sendTestNotification(this)"
                    class="w-full py-3 rounded-2xl text-xs font-bold text-sky-800 bg-sky-50 hover:bg-sky-100 border border-sky-200/80 shadow-xs transition active:scale-95 flex items-center justify-center gap-2">
                    <i class="ri-send-plane-fill text-sky-600 text-sm" aria-hidden="true"></i>
                    <span>ทดสอบส่งข้อความเข้ากลุ่มทันที</span>
                </button>
            </div>

            <!-- Setup Guide Box -->
            <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl space-y-3 text-xs">
                <h4 class="font-bold text-sky-300 flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="ri-lightbulb-flash-line text-base"></i>
                    <span>วิธีตั้งค่ากลุ่ม ODPC10-LSS</span>
                </h4>
                <ol class="list-decimal list-inside space-y-2 text-slate-300 font-light leading-relaxed pl-1">
                    <li>สร้างกลุ่มใหม่ใน Telegram ตั้งชื่อ <b class="text-white">ODPC10-LSS</b></li>
                    <li>เชิญบอทที่สร้างไว้เข้ากลุ่ม และตั้งค่าเป็น <b class="text-white">Admin</b></li>
                    <li>นำ <b class="text-white">Chat ID</b> ของกลุ่ม (ขึ้นต้นด้วยเครื่องหมายลบ เช่น <code class="bg-slate-800 px-1 py-0.5 rounded text-sky-300">-100xxxx</code>) มาวางในช่องด้านซ้าย</li>
                    <li>กดบันทึก แล้วกดทดสอบส่งข้อความ</li>
                </ol>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = isPassword ? 'ri-eye-off-line text-sm' : 'ri-eye-line text-sm';
        }
    }

    function sendTestNotification(btn) {
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="ri-loader-4-line animate-spin text-sm"></i> กำลังส่งข้อความ...`;

        fetch("{{ route('settings.telegram.test') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`✅ ${data.message}`);
            } else {
                alert(`❌ ${data.message}`);
            }
        })
        .catch(err => {
            alert(`❌ เกิดข้อผิดพลาด: ${err.message}`);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }
</script>
@endpush
