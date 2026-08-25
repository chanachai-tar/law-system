@extends('layouts.app')

@section('header_title')
    <i class="ri-scales-3-line text-indigo-600 text-lg" aria-hidden="true"></i>
    <span>บันทึกสำนวนกฎหมายใหม่</span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Breadcrumb / Title Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                บันทึกคำสั่งและสำนวนใหม่
            </h2>
            <p class="text-xs text-slate-600 mt-0.5">
                กรอกข้อมูลรายละเอียดสำนวนและบันทึกลำดับสถานะการดำเนินงาน
            </p>
        </div>

        <a href="{{ route('cases.index', ['law_type' => request('law_type')]) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-700 hover:text-slate-900 border border-slate-200 rounded-xl text-xs font-semibold shadow-sm hover:bg-slate-50 transition active:scale-95">
            <i class="ri-arrow-left-line text-sm" aria-hidden="true"></i>
            <span>กลับหน้ารายการ</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-emerald-600 text-xl" aria-hidden="true"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 text-xs font-bold text-rose-700">
                <i class="ri-error-warning-fill text-base" aria-hidden="true"></i>
                <span>กรุณาตรวจสอบข้อมูลที่กรอก:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cases.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- ส่วนที่ 1: รายละเอียดสำนวนหลัก -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
            <div class="bg-slate-50/70 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-folder-info-line text-indigo-600 text-base" aria-hidden="true"></i>
                    1. ข้อมูลคำสั่งและสำนวนหลัก
                </h3>
                <span class="text-[10px] font-semibold px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">
                    ขั้นตอนที่ 1
                </span>
            </div>

            <div class="p-6 sm:p-8 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- ประเภทสำนวน -->
                    <div>
                        <label for="law_type_select" class="block text-xs font-bold text-slate-700 mb-1.5">
                            ประเภทสำนวน <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="law_type" id="law_type_select" required
                                class="w-full h-11 pl-3.5 pr-9 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium appearance-none cursor-pointer">
                                <option value="">-- กรุณาเลือกประเภทสำนวน --</option>
                                <option value="1" {{ old('law_type', $lawType ?? '') == 1 ? 'selected' : '' }}>
                                    ตรวจสอบข้อเท็จจริง (ตส.)
                                </option>
                                <option value="2" {{ old('law_type', $lawType ?? '') == 2 ? 'selected' : '' }}>
                                    สอบสวนความรับผิดทางละเมิด (สล.)
                                </option>
                                <option value="3" {{ old('law_type', $lawType ?? '') == 3 ? 'selected' : '' }}>
                                    สอบสวนวินัย (สว.)
                                </option>
                            </select>
                            <i class="ri-arrow-down-s-line absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-base" aria-hidden="true"></i>
                        </div>
                    </div>

                    <!-- เลขที่สำนวน (รันอัตโนมัติ) -->
                    <div>
                        <label for="case_number_input" class="block text-xs font-bold text-slate-700 mb-1.5">
                            เลขที่หนังสือ / เลขที่สำนวน <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-500">
                                <i class="ri-file-list-3-line" aria-hidden="true"></i>
                            </div>
                            <input type="text" name="case_number" id="case_number_input"
                                data-running="{{ $autoCaseNumber ?? '001' }}" value="{{ old('case_number') }}"
                                readonly placeholder="เลือกระบุประเภทเพื่อรันเลขอัตโนมัติ..."
                                class="w-full h-11 pl-10 pr-4 bg-indigo-50/50 border border-indigo-200 rounded-xl font-bold text-indigo-700 outline-none text-xs cursor-not-allowed select-none">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">
                            * ระบบรันลำดับเลขอัตโนมัติตามประเภทและปี พ.ศ. {{ date('Y') + 543 }}
                        </p>
                    </div>

                    <!-- วันที่ดำเนินการ -->
                    <div>
                        <label for="incident_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            วันที่ดำเนินการ / รับเรื่อง <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="incident_date" id="incident_date" required value="{{ old('incident_date', date('Y-m-d')) }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs">
                    </div>

                    <!-- วันครบกำหนดรายงานตามระเบียบ (SLA) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="due_date_input" class="block text-xs font-bold text-slate-700">
                                วันครบกำหนดรายงานตามระเบียบ
                            </label>
                            <label for="no_due_date_checkbox" class="inline-flex items-center gap-1.5 cursor-pointer select-none group">
                                <input type="checkbox" id="no_due_date_checkbox" name="no_due_date" value="1"
                                    class="w-3.5 h-3.5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                <span class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition">ไม่สิ้นสุด</span>
                            </label>
                        </div>
                        <input type="date" name="due_date" id="due_date_input" value="{{ old('due_date') }}"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs">
                        <p id="no_due_date_hint" class="hidden text-[10px] text-indigo-600 font-semibold mt-1 flex items-center gap-1">
                            <i class="ri-checkbox-circle-fill" aria-hidden="true"></i> ตั้งค่าสำนวนนี้เป็น "ไม่มีกำหนดเวลาสิ้นสุด / ไม่สิ้นสุด"
                        </p>
                    </div>

                    <!-- เรียน (To) -->
                    <div class="md:col-span-2">
                        <label for="to_input" class="block text-xs font-bold text-slate-700 mb-1.5">
                            เรียน (หน่วยงาน / บุคคลที่ส่งเรื่อง)
                        </label>
                        <input type="text" name="to" id="to_input" value="{{ old('to') }}"
                            placeholder="ระบุหน่วยงานหรือบุคคลที่เรียน (ถ้ามี)"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs">
                    </div>

                    <!-- เรื่อง / รายละเอียดสำนวน -->
                    <div class="md:col-span-2">
                        <label for="subject_input" class="block text-xs font-bold text-slate-700 mb-1.5">
                            เรื่อง / รายละเอียดสำนวน <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="subject" id="subject_input" required value="{{ old('subject') }}"
                            placeholder="ระบุชื่อเรื่องหรือรายละเอียดสำคัญของสำนวน"
                            class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs">
                    </div>

                    <!-- รายละเอียดอื่นๆ -->
                    <div class="md:col-span-2">
                        <label for="description_input" class="block text-xs font-bold text-slate-700 mb-1.5">
                            รายละเอียดอื่นๆ (ถ้ามี)
                        </label>
                        <textarea name="description" id="description_input" rows="3" placeholder="ระบุรายละเอียดหรือข้อสังเกตเพิ่มเติม..."
                            class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ส่วนที่ 2: สถานะการดำเนินการ (Timeline) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
            <div class="bg-slate-50/70 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-history-line text-indigo-600 text-base" aria-hidden="true"></i>
                    2. ลำดับสถานะการดำเนินการ (Timeline)
                </h3>
                <button type="button" id="add-step" aria-label="เพิ่มขั้นตอนใหม่"
                    class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-1.5 rounded-xl transition shadow-sm flex items-center gap-1 font-semibold active:scale-95">
                    <i class="ri-add-line" aria-hidden="true"></i> เพิ่มขั้นตอน / ครั้งที่
                </button>
            </div>

            <div id="steps-container" class="p-6 sm:p-8 space-y-4">
                <!-- ครั้งที่ 1 -->
                <div class="step-card p-5 border border-slate-200/80 rounded-2xl bg-slate-50/50 relative space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                        <div class="md:col-span-1">
                            <span class="block text-[10px] font-bold text-slate-500 uppercase">ครั้งที่</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm shadow-sm mt-1">
                                1
                            </span>
                            <input type="hidden" name="steps[0][num]" value="1">
                        </div>
                        <div class="md:col-span-6">
                            <label for="step_desc_0" class="block text-[10px] font-bold text-slate-600 uppercase mb-1">รายละเอียดการดำเนินการ</label>
                            <textarea name="steps[0][description]" id="step_desc_0" rows="3"
                                class="w-full p-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs resize-none"
                                placeholder="ระบุรายละเอียดผลการดำเนินงานในครั้งนี้..."></textarea>
                        </div>
                        <div class="md:col-span-5">
                            <label for="step_files_0" class="block text-[10px] font-bold text-indigo-700 uppercase mb-1 flex items-center gap-1">
                                <i class="ri-file-pdf-line" aria-hidden="true"></i> แนบไฟล์เอกสาร PDF
                            </label>
                            <input type="file" name="steps[0][files][]" id="step_files_0" multiple accept=".pdf"
                                class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit & Cancel Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('cases.index', ['law_type' => request('law_type')]) }}"
                class="px-6 py-2.5 rounded-xl font-semibold text-xs text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition active:scale-95">
                ยกเลิก
            </a>
            <button type="submit" id="submitBtn"
                class="px-8 py-2.5 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 transition active:scale-95 flex items-center gap-2">
                <i id="btnIcon" class="ri-save-3-line text-sm" aria-hidden="true"></i>
                <span id="btnText">บันทึกข้อมูลสำนวน</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (form.checkValidity()) {
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                    if (btnIcon) btnIcon.className = 'ri-loader-4-line animate-spin text-sm';
                    if (btnText) btnText.textContent = 'กำลังบันทึกข้อมูล...';
                }
            });
        }

        const typeSelect = document.getElementById('law_type_select');
        const caseInput = document.getElementById('case_number_input');
        const runningNum = caseInput.dataset.running || '001';

        const codeMapping = {
            '1': 'ตส.',
            '2': 'สล.',
            '3': 'สว.'
        };

        typeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const code = codeMapping[selectedValue];

            if (code) {
                caseInput.value = `${code}${runningNum}`;
            } else {
                caseInput.value = "";
                caseInput.placeholder = "เลือกระบุประเภทเพื่อรันเลขอัตโนมัติ...";
            }
        });

        if (typeSelect.value) {
            typeSelect.dispatchEvent(new Event('change'));
        }

        const noDueCheckbox = document.getElementById('no_due_date_checkbox');
        const dueDateInput = document.getElementById('due_date_input');
        const noDueHint = document.getElementById('no_due_date_hint');

        if (noDueCheckbox && dueDateInput) {
            noDueCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    dueDateInput.value = '';
                    dueDateInput.disabled = true;
                    dueDateInput.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'opacity-60');
                    dueDateInput.classList.remove('bg-slate-50');
                    if (noDueHint) noDueHint.classList.remove('hidden');
                } else {
                    dueDateInput.disabled = false;
                    dueDateInput.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'opacity-60');
                    dueDateInput.classList.add('bg-slate-50');
                    if (noDueHint) noDueHint.classList.add('hidden');
                }
            });
        }

        let stepCount = 1;
        document.getElementById('add-step').addEventListener('click', function() {
            const container = document.getElementById('steps-container');
            const div = document.createElement('div');
            const currentNum = stepCount + 1;
            div.className = "step-card p-5 border border-slate-200/80 rounded-2xl bg-slate-50/50 relative space-y-3 animate-fade-in";
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                    <div class="md:col-span-1">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase">ครั้งที่</span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold text-sm shadow-sm mt-1">
                            ${currentNum}
                        </span>
                        <input type="hidden" name="steps[${stepCount}][num]" value="${currentNum}">
                    </div>
                    <div class="md:col-span-6">
                        <label for="step_desc_${stepCount}" class="block text-[10px] font-bold text-slate-600 uppercase mb-1">รายละเอียดการดำเนินการ</label>
                        <textarea name="steps[${stepCount}][description]" id="step_desc_${stepCount}" rows="3"
                            class="w-full p-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs resize-none"
                            placeholder="ระบุรายละเอียดผลการดำเนินงานในครั้งนี้..."></textarea>
                    </div>
                    <div class="md:col-span-5">
                        <label for="step_files_${stepCount}" class="block text-[10px] font-bold text-indigo-700 uppercase mb-1 flex items-center gap-1">
                            <i class="ri-file-pdf-line" aria-hidden="true"></i> แนบไฟล์เอกสาร PDF
                        </label>
                        <input type="file" name="steps[${stepCount}][files][]" id="step_files_${stepCount}" multiple accept=".pdf"
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()" aria-label="ลบขั้นตอนนี้"
                    class="absolute -top-2.5 -right-2.5 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-rose-700 transition shadow-md">
                    <i class="ri-close-line" aria-hidden="true"></i>
                </button>
            `;
            container.appendChild(div);
            stepCount++;
        });
    });
</script>
@endpush
