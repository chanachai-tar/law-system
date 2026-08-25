@extends('layouts.app')

@section('header_title')
    <i class="ri-edit-2-line text-indigo-600 text-lg"></i>
    <span>แก้ไขคำสั่งแต่งตั้ง: {{ $order->order_number }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Breadcrumb / Title Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                แก้ไขคำสั่ง: {{ $order->order_number }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                ปรับปรุงข้อมูลคำสั่งหรืออัปโหลดไฟล์เอกสารใหม่
            </p>
        </div>

        <a href="{{ route('orders.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 rounded-xl text-xs font-semibold shadow-sm hover:bg-slate-50 transition active:scale-95">
            <i class="ri-arrow-left-line text-sm"></i>
            <span>กลับแฟ้มคำสั่ง</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 text-xs font-bold text-rose-700">
                <i class="ri-error-warning-fill text-base"></i>
                <span>กรุณาตรวจสอบข้อมูลที่กรอก:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
            <div class="bg-slate-50/70 px-6 py-4 border-b border-slate-100">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-file-info-line text-indigo-600 text-base"></i>
                    รายละเอียดข้อมูลคำสั่ง
                </h3>
            </div>

            <div class="p-6 sm:p-8 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- เลขที่คำสั่ง -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            เลขที่คำสั่ง <span class="text-rose-500">*</span>
                        </label>
                        <input id="orderNumberInput" type="text" name="order_number" required
                            value="{{ old('order_number', $order->order_number) }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium {{ $errors->has('order_number') ? 'border-rose-500 ring-1 ring-rose-300' : '' }}">
                        <p id="order-number-feedback" class="mt-1 text-xs" role="alert" aria-live="polite"></p>
                    </div>

                    <!-- ลงวันที่ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            ลงวันที่ <span class="text-rose-500">*</span>
                        </label>
                        <input id="orderDateInput" type="date" name="order_date" required
                            value="{{ old('order_date', optional($order->order_date)->format('Y-m-d')) }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs">
                    </div>

                    <!-- เรื่อง -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            ชื่อเรื่องตามคำสั่ง <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="subject" required placeholder="ระบุชื่อเรื่องตามคำสั่ง"
                            value="{{ old('subject', $order->subject) }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium">
                    </div>

                    <!-- เจ้าของเรื่อง -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            เจ้าของเรื่อง / กลุ่มงาน <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="owner" required placeholder="ระบุกลุ่มงานหรือหน่วยงาน"
                            value="{{ old('owner', $order->owner) }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium">
                    </div>

                    <!-- สถานะ -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            สถานะคำสั่ง <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium">
                            <option value="active" {{ old('status', $order->status) == 'active' ? 'selected' : '' }}>มีผลบังคับใช้</option>
                            <option value="inactive" {{ old('status', $order->status) == 'inactive' ? 'selected' : '' }}>ยกเลิก/สิ้นสุด</option>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>อยู่ระหว่างเสนอลงนาม</option>
                        </select>
                    </div>

                    <!-- แนบไฟล์ใหม่ -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-xs font-bold text-indigo-700 mb-1 flex items-center gap-1">
                            <i class="ri-attachment-line text-sm"></i>
                            อัปโหลดไฟล์คำสั่งใหม่ (เว้นว่างถ้าไม่ต้องการเปลี่ยน)
                        </label>
                        @if ($order->file_path)
                            <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                                <i class="ri-file-pdf-fill text-rose-600 text-base"></i>
                                <span class="text-slate-600">ไฟล์ปัจจุบัน:</span>
                                <a href="{{ route('orders.download', $order->id) }}" class="text-indigo-600 hover:underline font-semibold">
                                    ดาวน์โหลดไฟล์เดิม
                                </a>
                            </div>
                        @endif
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('orders.index') }}"
                class="px-6 py-2.5 rounded-xl font-semibold text-xs text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition active:scale-95">
                ยกเลิก
            </a>
            <button id="orderSubmitBtn" type="submit"
                class="px-8 py-2.5 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 transition active:scale-95 flex items-center gap-2">
                <i id="orderSubmitIcon" class="ri-save-3-line text-sm"></i>
                <span id="orderSubmitText">บันทึกการแก้ไข</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const orderNumber = document.getElementById('orderNumberInput');
        const orderDate = document.getElementById('orderDateInput');
        const feedback = document.getElementById('order-number-feedback');
        const submitBtn = document.getElementById('orderSubmitBtn');
        const submitIcon = document.getElementById('orderSubmitIcon');
        const submitText = document.getElementById('orderSubmitText');
        const checkUrl = "{{ route('orders.checkDuplicate') }}";
        const currentId = "{{ $order->id }}";

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (form.checkValidity() && !submitBtn.disabled) {
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                    if (submitIcon) submitIcon.className = 'ri-loader-4-line animate-spin text-sm';
                    if (submitText) submitText.textContent = 'กำลังบันทึกข้อมูล...';
                }
            });
        }

        let pending = null;

        async function checkDuplicate() {
            const num = orderNumber.value.trim();
            const date = orderDate.value;
            feedback.textContent = '';
            orderNumber.classList.remove('border-rose-500', 'ring-1', 'ring-rose-300');
            submitBtn.disabled = false;

            if (!num || !date) return;

            if (pending) clearTimeout(pending);
            pending = setTimeout(async () => {
                try {
                    const resp = await fetch(
                        `${checkUrl}?order_number=${encodeURIComponent(num)}&order_date=${encodeURIComponent(date)}&exclude_id=${currentId}`
                    );
                    if (!resp.ok) return;
                    const json = await resp.json();
                    if (json.duplicate) {
                        feedback.textContent = `⚠️ เลขที่คำสั่ง "${num}" มีอยู่แล้วในปี ${json.year}`;
                        feedback.className = 'mt-1 text-xs text-rose-600 font-medium';
                        orderNumber.classList.add('border-rose-500', 'ring-1', 'ring-rose-300');
                        submitBtn.disabled = true;
                    } else {
                        feedback.textContent = '✓ เลขที่คำสั่งนี้สามารถใช้ได้';
                        feedback.className = 'mt-1 text-xs text-emerald-600 font-medium';
                        submitBtn.disabled = false;
                    }
                } catch (err) {
                    console.error(err);
                }
            }, 350);
        }

        if (orderNumber) orderNumber.addEventListener('input', checkDuplicate);
        if (orderDate) orderDate.addEventListener('change', checkDuplicate);
    });
</script>
@endpush
