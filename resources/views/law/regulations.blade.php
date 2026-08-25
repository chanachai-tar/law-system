@extends('layouts.app')

@section('header_title')
    <i class="ri-book-read-line text-indigo-600 text-lg"></i>
    <span>คลังระเบียบ กฎหมาย และหนังสือเวียน</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                คลังระเบียบ กฎหมาย และหนังสือเวียน
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                ศูนย์รวบรวมระเบียบข้อบังคับ มติคณะรัฐมนตรี และหนังสือเวียนแนวทางปฏิบัติด้านกฎหมาย
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" onclick="openUploadRegulationModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition active:scale-95">
                <i class="ri-upload-cloud-2-line text-base"></i>
                <span>เพิ่มระเบียบ/หนังสือเวียนใหม่</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <i class="ri-checkbox-circle-fill text-lg"></i>
            </div>
            <p class="text-xs font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Category Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. ระเบียบ / ข้อบังคับ -->
        <a href="{{ route('regulations.index', ['category' => 'regulation']) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('category') === 'regulation' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200/70 hover:border-indigo-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-book-2-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ $stats['regulation'] }} รายการ
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                ระเบียบ / ข้อบังคับ
            </p>
            <p class="text-[11px] text-slate-400 mt-1">ระเบียบปฏิบัติและข้อบังคับทางราชการ</p>
        </a>

        <!-- 2. มติ ครม. -->
        <a href="{{ route('regulations.index', ['category' => 'cabinet_resolution']) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('category') === 'cabinet_resolution' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-slate-200/70 hover:border-amber-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-government-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                    {{ $stats['cabinet_resolution'] }} รายการ
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-amber-600 transition-colors">
                มติคณะรัฐมนตรี (ครม.)
            </p>
            <p class="text-[11px] text-slate-400 mt-1">มติ ครม. ที่เกี่ยวข้องกับนิติการ</p>
        </a>

        <!-- 3. หนังสือเวียน -->
        <a href="{{ route('regulations.index', ['category' => 'circular_letter']) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('category') === 'circular_letter' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-slate-200/70 hover:border-emerald-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-mail-send-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                    {{ $stats['circular_letter'] }} รายการ
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">
                หนังสือเวียน / แนวทาง
            </p>
            <p class="text-[11px] text-slate-400 mt-1">หนังสือซักซ้อมความเข้าใจและแนวปฏิบัติ</p>
        </a>

        <!-- 4. พระราชบัญญัติ / กฎหมาย -->
        <a href="{{ route('regulations.index', ['category' => 'general_law']) }}"
            class="bg-white p-5 rounded-3xl border transition-all duration-200 hover:shadow-md group {{ request('category') === 'general_law' ? 'border-sky-500 ring-2 ring-sky-500/20' : 'border-slate-200/70 hover:border-sky-300' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="ri-scales-3-line"></i>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                    {{ $stats['general_law'] }} รายการ
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 group-hover:text-sky-600 transition-colors">
                พ.ร.บ. / กฎหมายทั่วไป
            </p>
            <p class="text-[11px] text-slate-400 mt-1">กฎหมายหลักและพระราชบัญญัติ</p>
        </a>
    </div>

    <!-- Category Tabs Navigation -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('regulations.index') }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            ทั้งหมด ({{ $stats['all'] }})
        </a>
        <a href="{{ route('regulations.index', ['category' => 'regulation']) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('category') === 'regulation' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            ระเบียบ / ข้อบังคับ ({{ $stats['regulation'] }})
        </a>
        <a href="{{ route('regulations.index', ['category' => 'cabinet_resolution']) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('category') === 'cabinet_resolution' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            มติ ครม. ({{ $stats['cabinet_resolution'] }})
        </a>
        <a href="{{ route('regulations.index', ['category' => 'circular_letter']) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('category') === 'circular_letter' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            หนังสือเวียน ({{ $stats['circular_letter'] }})
        </a>
        <a href="{{ route('regulations.index', ['category' => 'general_law']) }}"
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap {{ request('category') === 'general_law' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
            พ.ร.บ. / กฎหมาย ({{ $stats['general_law'] }})
        </a>
    </div>

    <!-- Search Card -->
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-200/70">
        <form method="GET" action="{{ route('regulations.index') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="relative flex-1">
                <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ค้นหาชื่อระเบียบ, มติ ครม., หรือหัวข้อหนังสือเวียน..."
                    class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
            </div>

            <button type="submit"
                class="h-11 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-200 active:scale-95">
                <i class="ri-search-line text-sm"></i>
                <span>ค้นหา</span>
            </button>

            @if(request('search') || request('category'))
                <a href="{{ route('regulations.index') }}"
                    class="h-11 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 active:scale-95">
                    <i class="ri-refresh-line text-sm"></i>
                    <span>ล้างการค้นหา</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Regulations Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase border-b border-slate-200/70">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">ชื่อเอกสาร / ระเบียบ</th>
                        <th scope="col" class="px-6 py-3.5">หมวดหมู่</th>
                        <th scope="col" class="px-6 py-3.5">ขนาดไฟล์</th>
                        <th scope="col" class="px-6 py-3.5">ผู้อัปโหลด</th>
                        <th scope="col" class="px-6 py-3.5">วันที่เพิ่ม</th>
                        <th scope="col" class="px-6 py-3.5 text-center">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($regulations as $reg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0 border border-indigo-100 shadow-sm">
                                        <i class="ri-file-text-line" aria-hidden="true"></i>
                                    </div>
                                    <div class="min-w-0 max-w-md">
                                        <p class="font-bold text-slate-800" title="{{ $reg->title }}">
                                            {{ $reg->title }}
                                        </p>
                                        @if($reg->description)
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $reg->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border
                                    @if($reg->category === 'regulation') bg-indigo-50 text-indigo-700 border-indigo-200
                                    @elseif($reg->category === 'cabinet_resolution') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($reg->category === 'circular_letter') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @else bg-sky-50 text-sky-700 border-sky-200 @endif">
                                    {{ $reg->category_name }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                {{ $reg->file_size ?? '-' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-slate-600">
                                    <i class="ri-user-3-line text-indigo-500" aria-hidden="true"></i>
                                    <span>{{ $reg->user?->name ?? 'ส่วนกลาง' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                {{ thaidate($reg->created_at, 'short') }}
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $reg->file_path) }}" target="_blank" rel="noopener noreferrer"
                                        aria-label="เปิดดูเอกสาร {{ $reg->title }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-xl font-bold text-xs border border-indigo-200/80 transition shadow-sm active:scale-95">
                                        <i class="ri-download-2-line" aria-hidden="true"></i>
                                        <span>เปิดดู / โหลด</span>
                                    </a>

                                    @if(Auth::user()?->role === 'admin' || Auth::id() === $reg->user_id)
                                        <form method="POST" action="{{ route('regulations.destroy', $reg->id) }}" onsubmit="return confirm('ต้องการลบเอกสารนี้หรือไม่?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" aria-label="ลบเอกสาร {{ $reg->title }}">
                                                <i class="ri-delete-bin-line text-sm" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-14 h-14 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="ri-book-open-line"></i>
                                </div>
                                <p class="font-bold text-xs text-slate-600">ยังไม่มีเอกสารในหมวดหมู่นี้</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">ท่านสามารถกดปุ่ม "เพิ่มระเบียบ/หนังสือเวียนใหม่" ด้านบนเพื่ออัปโหลดเอกสาร</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($regulations->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $regulations->links() }}
            </div>
        @endif
    </div>

    <!-- Upload Regulation Modal -->
    <div id="uploadRegulationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 space-y-5 animate-in fade-in zoom-in-95 duration-150" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                        <i class="ri-upload-cloud-2-line"></i>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-base">เพิ่มระเบียบ / หนังสือเวียนใหม่</h3>
                </div>
                <button type="button" onclick="closeUploadRegulationModal()" class="text-slate-400 hover:text-slate-600 text-lg p-1">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('regulations.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ชื่อระเบียบ / หัวเรื่องหนังสือเวียน <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="เช่น ระเบียบกระทรวงสาธารณสุขว่าด้วย..."
                        class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">หมวดหมู่เอกสาร <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition">
                        <option value="regulation">ระเบียบ / ข้อบังคับ</option>
                        <option value="cabinet_resolution">มติคณะรัฐมนตรี (ครม.)</option>
                        <option value="circular_letter">หนังสือเวียน / แนวทางปฏิบัติ</option>
                        <option value="general_law">พระราชบัญญัติ / กฎหมายทั่วไป</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">รายละเอียด / สรุปสาระสำคัญ (ย่อ)</label>
                    <textarea name="description" rows="3" placeholder="ระบุเนื้อหาย่อหรือขอบเขตการบังคับใช้..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">แนบไฟล์เอกสาร (PDF, Docx, Zip ไม่เกิน 20MB) <span class="text-rose-500">*</span></label>
                    <input type="file" name="attachment" required accept=".pdf,.doc,.docx,.zip"
                        class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeUploadRegulationModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                        ยกเลิก
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-200 transition">
                        บันทึกเอกสาร
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function openUploadRegulationModal() {
        const modal = document.getElementById('uploadRegulationModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeUploadRegulationModal() {
        const modal = document.getElementById('uploadRegulationModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('uploadRegulationModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeUploadRegulationModal();
                }
            });
        }
    });
</script>
@endpush
