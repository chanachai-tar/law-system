@extends('layouts.app')

@section('header_title')
    <i class="ri-edit-2-line text-indigo-600 text-lg"></i>
    <span>บันทึกความคืบหน้าสำนวน: {{ $case->case_number }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Breadcrumb / Title Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                ดำเนินการสำนวน: {{ $case->case_number }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                แก้ไขข้อมูลพื้นฐานและบันทึกลำดับความคืบหน้าเพิ่มเติม
            </p>
        </div>

        <a href="{{ route('cases.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80 rounded-xl text-xs font-semibold shadow-sm hover:bg-slate-50 transition active:scale-95">
            <i class="ri-arrow-left-line text-sm"></i>
            <span>กลับหน้ารายการ</span>
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-emerald-600 text-xl"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('cases.update', $case->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- ข้อมูลสำนวนเดิม -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-slate-200/70 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-folder-info-line text-indigo-600 text-base"></i>
                    ข้อมูลสำนวนพื้นฐาน
                </h3>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ law_type($case->law_type) }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">เลขที่สำนวน</label>
                    <p class="text-sm font-extrabold text-indigo-600 bg-indigo-50/60 px-3.5 py-2.5 rounded-xl border border-indigo-100/80">
                        {{ $case->case_number }}
                    </p>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">ประเภท</label>
                    <p class="text-sm font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/80">
                        {{ law_type($case->law_type) }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label for="edit_subject_input" class="block text-xs font-bold text-slate-700 mb-1">เรื่อง / รายละเอียด</label>
                    <input type="text" name="subject" id="edit_subject_input" value="{{ $case->subject }}" required
                        class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium">
                </div>

                <div>
                    <label for="edit_to_input" class="block text-xs font-bold text-slate-700 mb-1">เจ้าของเรื่อง / เรียน</label>
                    <input type="text" name="to" id="edit_to_input" value="{{ $case->to }}"
                        class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="edit_due_date_input" class="block text-xs font-bold text-slate-700">
                            วันครบกำหนดรายงานตามระเบียบ
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer select-none group">
                            <input type="checkbox" id="edit_no_due_date_checkbox" name="no_due_date" value="1" {{ !$case->due_date ? 'checked' : '' }}
                                class="w-3.5 h-3.5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer">
                            <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-600 transition">ไม่สิ้นสุด</span>
                        </label>
                    </div>
                    <input type="date" name="due_date" id="edit_due_date_input" value="{{ $case->due_date ? $case->due_date->format('Y-m-d') : '' }}"
                        {{ !$case->due_date ? 'disabled' : '' }}
                        class="w-full h-11 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs font-medium {{ !$case->due_date ? 'bg-slate-100 text-slate-400 cursor-not-allowed opacity-60' : '' }}">
                    @if($case->due_date)
                        @php $days = $case->days_remaining; @endphp
                        <p id="edit_due_status_badge" class="mt-1">
                            @if($days < 0)
                                <span class="inline-flex items-center gap-1 text-[10px] text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                    <i class="ri-error-warning-line" aria-hidden="true"></i> เกินกำหนด {{ abs($days) }} วัน
                                </span>
                            @elseif($days <= 7)
                                <span class="inline-flex items-center gap-1 text-[10px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                    <i class="ri-time-line" aria-hidden="true"></i> เหลือ {{ $days }} วัน
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                    <i class="ri-checkbox-circle-line" aria-hidden="true"></i> เหลือ {{ $days }} วัน
                                </span>
                            @endif
                        </p>
                    @endif
                    <p id="edit_no_due_hint" class="{{ $case->due_date ? 'hidden' : '' }} text-[10px] text-indigo-600 font-semibold mt-1 flex items-center gap-1">
                        <i class="ri-checkbox-circle-fill" aria-hidden="true"></i> สำนวนนี้ถูกตั้งค่าเป็น "ไม่มีกำหนดเวลาสิ้นสุด / ไม่สิ้นสุด"
                    </p>
                </div>
            </div>
        </div>

        <!-- ประวัติการดำเนินการเดิม -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl shadow-sm border border-slate-200/70 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                <i class="ri-history-line text-indigo-600 text-base" aria-hidden="true"></i>
                ประวัติการดำเนินการเดิม ({{ $case->steps->count() }} ครั้ง)
            </h3>

            <div class="space-y-3">
                @foreach ($case->steps->sortBy('step_num') as $step)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[10px] border border-indigo-100">
                                ครั้งที่ {{ $step->step_num }}
                            </span>
                            <div class="text-[11px] text-slate-500">
                                <span>{{ $step->user->name ?? 'ไม่ระบุ' }}</span> • 
                                <span>{{ thaidate($step->created_at) }}</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-700 leading-relaxed bg-white p-3 rounded-xl border border-slate-100">
                            {{ $step->description }}
                        </p>

                        @if ($step->files->count() > 0)
                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach ($step->files as $file)
                                    <a href="{{ route('files.view', base64_encode($file->file_path)) }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-rose-600 rounded-xl border border-rose-200 text-[11px] font-semibold hover:bg-rose-600 hover:text-white transition shadow-sm">
                                        <i class="ri-file-pdf-fill text-xs" aria-hidden="true"></i>
                                        <span>เปิดเอกสาร PDF</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ส่วนเพิ่มขั้นตอนใหม่ (รันเลขให้อัตโนมัติ) -->
        <div class="bg-white rounded-3xl shadow-md border-2 border-indigo-500 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4 text-white flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-add-circle-line text-base" aria-hidden="true"></i>
                    บันทึกการดำเนินการเพิ่มเติม (ครั้งที่ {{ $case->steps->max('step_num') + 1 }})
                </h3>
                <span class="text-[10px] font-bold px-2 py-0.5 bg-white/20 rounded-full backdrop-blur-sm">
                    ขั้นตอนล่าสุด
                </span>
            </div>

            <div class="p-6 sm:p-7 space-y-4">
                <input type="hidden" name="new_step_num" value="{{ $case->steps->max('step_num') + 1 }}">

                <div>
                    <label for="new_step_description" class="block text-xs font-bold text-slate-700 mb-1.5">
                        รายละเอียดการดำเนินการครั้งนี้ <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="new_step_description" required rows="3"
                        placeholder="ระบุรายละเอียดผลการดำเนินงานหรือความคืบหน้า..."
                        class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-xs resize-none"></textarea>
                </div>

                <div>
                    <label for="new_step_files" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1">
                        <i class="ri-file-pdf-line text-indigo-600" aria-hidden="true"></i>
                        แนบไฟล์ PDF เพิ่มเติม
                    </label>
                    <input type="file" name="files[]" id="new_step_files" multiple accept=".pdf"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Submit & Cancel Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
            <div>
                @if($case->status !== 'completed')
                    <button type="button" onclick="openEditCloseModal()"
                        class="px-5 py-2.5 rounded-xl font-semibold text-xs text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 transition active:scale-95 flex items-center gap-1.5 shadow-sm">
                        <i class="ri-checkbox-circle-line text-sm"></i>
                        <span>ปิดสำนวนนี้</span>
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('cases.index') }}"
                    class="px-6 py-2.5 rounded-xl font-semibold text-xs text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition active:scale-95">
                    ยกเลิก
                </a>
                <button type="submit" id="submitBtn"
                    class="px-8 py-2.5 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-200 transition active:scale-95 flex items-center gap-2">
                    <i id="btnIcon" class="ri-save-3-line text-sm"></i>
                    <span id="btnText">บันทึกข้อมูลเพิ่มเติม</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Close Case Modal for Edit Page -->
    <div id="editCloseCaseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 text-left border border-slate-100 space-y-4 animate-in fade-in zoom-in-95 duration-150" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            บันทึกปิดสำนวน: {{ $case->case_number }}
                        </h3>
                        <p class="text-[11px] text-slate-400">ระบุสรุปผลการพิจารณาเพื่อสิ้นสุดการดำเนินการ</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditCloseModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('cases.close', $case->id) }}" class="space-y-3.5">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">สรุปผลการพิจารณา / ผลคำวินิจฉัย</label>
                    <textarea name="outcome_summary" rows="2" placeholder="เช่น คณะกรรมการสอบสวนมีความเห็นว่า..."
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">ผล / บทลงโทษ (ถ้ามี)</label>
                        <input type="text" name="penalty_type" placeholder="เช่น ภาคทัณฑ์, ยุติเรื่อง"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">ยอดเงินชดใช้ (บาท)</label>
                        <input type="number" step="0.01" name="damage_amount" placeholder="เช่น 50000.00"
                            class="w-full h-9 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>

                <div class="flex gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeEditCloseModal()"
                        class="flex-1 px-4 py-2.5 text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-200 transition active:scale-95">
                        ยืนยันปิดสำนวน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditCloseModal() {
        const modal = document.getElementById('editCloseCaseModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeEditCloseModal() {
        const modal = document.getElementById('editCloseCaseModal');
        if (modal) modal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editCloseCaseModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeEditCloseModal();
            });
        }

        const editNoDueCheckbox = document.getElementById('edit_no_due_date_checkbox');
        const editDueDateInput = document.getElementById('edit_due_date_input');
        const editNoDueHint = document.getElementById('edit_no_due_hint');
        const editDueStatusBadge = document.getElementById('edit_due_status_badge');

        if (editNoDueCheckbox && editDueDateInput) {
            editNoDueCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    editDueDateInput.value = '';
                    editDueDateInput.disabled = true;
                    editDueDateInput.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'opacity-60');
                    editDueDateInput.classList.remove('bg-slate-50');
                    if (editNoDueHint) editNoDueHint.classList.remove('hidden');
                    if (editDueStatusBadge) editDueStatusBadge.classList.add('hidden');
                } else {
                    editDueDateInput.disabled = false;
                    editDueDateInput.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed', 'opacity-60');
                    editDueDateInput.classList.add('bg-slate-50');
                    if (editNoDueHint) editNoDueHint.classList.add('hidden');
                    if (editDueStatusBadge) editDueStatusBadge.classList.remove('hidden');
                }
            });
        }

        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                if (form.checkValidity()) {
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                    if (btnIcon) {
                        btnIcon.className = 'ri-loader-4-line animate-spin text-sm';
                    }
                    if (btnText) {
                        btnText.textContent = 'กำลังบันทึกข้อมูล...';
                    }
                }
            });
        }
    });
</script>
@endpush
