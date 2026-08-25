@extends('layouts.app')

@section('header_title')
    <i class="ri-file-list-3-line text-indigo-600 text-lg"></i>
    <span>คำสั่งแต่งตั้งคณะกรรมการ</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Actions & Flash Messages -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                แฟ้มคำสั่งแต่งตั้งคณะกรรมการ
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                รวบรวมและสืบค้นคำสั่งแต่งตั้งคณะกรรมการของหน่วยงาน
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('orders.export') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-emerald-200 transition-all active:scale-95">
                <i class="ri-file-excel-2-line text-base"></i>
                <span>ส่งออก Excel</span>
            </a>
            <a href="{{ route('orders.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition-all active:scale-95">
                <i class="ri-add-circle-line text-base"></i>
                <span>เพิ่มคำสั่งใหม่</span>
            </a>
        </div>
    </div>

    <!-- Alert Message -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-emerald-600 text-xl"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filter Area -->
    <div class="bg-white p-5 sm:p-6 rounded-3xl shadow-sm border border-slate-200/70">
        <form action="{{ route('orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="order_search_input" class="block mb-1.5 font-bold text-xs text-slate-700">คำค้นหา</label>
                <div class="relative flex items-center">
                    <i class="ri-search-line absolute left-3.5 text-slate-400 text-sm pointer-events-none" aria-hidden="true"></i>
                    <input type="text" name="search" id="order_search_input" value="{{ request('search') }}"
                        placeholder="ค้นหาเลขที่คำสั่ง หรือชื่อเรื่อง..."
                        style="border-radius: 1rem !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                        class="w-full h-11 pl-9 pr-3.5 bg-slate-50 border border-slate-200/70 rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs text-slate-800 outline-none transition-all">
                </div>
            </div>

            <div>
                <label for="order_status_select" class="block mb-1.5 font-bold text-xs text-slate-700">สถานะคำสั่ง</label>
                <div class="relative flex items-center">
                    <select name="status" id="order_status_select"
                        style="border-radius: 1rem !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-color: #f8fafc !important; border: 1px solid rgba(226, 232, 240, 0.8) !important;"
                        class="w-full h-11 pl-3.5 pr-8 bg-slate-50 border border-slate-200/70 rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs font-medium text-slate-800 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">ทุกสถานะ</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>มีผลบังคับใช้</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ยกเลิก/สิ้นสุด</option>
                    </select>
                    <i class="ri-arrow-down-s-line absolute right-3 text-slate-400 pointer-events-none text-sm" aria-hidden="true"></i>
                </div>
            </div>

            <div>
                <button type="submit" aria-label="กรองข้อมูลคำสั่ง"
                    class="w-full h-11 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-md shadow-slate-800/20 active:scale-95">
                    <i class="ri-filter-3-line text-sm" aria-hidden="true"></i>
                    <span>กรองข้อมูล</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Area -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase border-b border-slate-200/70">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-center w-16">ลำดับ</th>
                        <th scope="col" class="px-6 py-3.5">เลขที่คำสั่ง</th>
                        <th scope="col" class="px-6 py-3.5">ลงวันที่</th>
                        <th scope="col" class="px-6 py-3.5">เรื่อง</th>
                        <th scope="col" class="px-6 py-3.5">เจ้าของเรื่อง</th>
                        <th scope="col" class="px-6 py-3.5 text-center">สถานะ</th>
                        <th scope="col" class="px-6 py-3.5 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $index => $order)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-500 font-medium">
                                {{ $orders->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ thaidate($order->order_date, 'short') }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 max-w-sm">
                                <a href="{{ route('orders.viewPdf', $order->id) }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline line-clamp-2">
                                    {{ $order->subject }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-[11px] bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg border border-slate-200/70 font-medium">
                                    {{ $order->owner }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if ($order->status == 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        มีผลบังคับใช้
                                    </span>
                                @elseif($order->status == 'inactive')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        ยกเลิก/สิ้นสุด
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        รอดำเนินการ
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-1.5">
                                    @if ($order->file_path)
                                        <a href="{{ route('orders.download', $order->id) }}"
                                            class="w-8 h-8 flex items-center justify-center text-emerald-600 hover:bg-emerald-50 rounded-xl border border-emerald-100 transition shadow-sm"
                                            title="ดาวน์โหลดไฟล์เอกสาร"
                                            aria-label="ดาวน์โหลดไฟล์คำสั่ง {{ $order->order_number }}">
                                            <i class="ri-download-cloud-2-line text-sm" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('orders.edit', ['id' => $order->id]) }}"
                                        class="w-8 h-8 flex items-center justify-center text-indigo-600 hover:bg-indigo-50 rounded-xl border border-indigo-100 transition shadow-sm"
                                        title="แก้ไขคำสั่ง"
                                        aria-label="แก้ไขคำสั่ง {{ $order->order_number }}">
                                        <i class="ri-edit-line text-sm" aria-hidden="true"></i>
                                    </a>
                                    <form action="{{ route('orders.destroy', ['id' => $order->id]) }}" method="POST"
                                        class="inline" onsubmit="return confirm('ยืนยันการลบคำสั่งนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-rose-600 hover:bg-rose-50 rounded-xl border border-rose-100 transition shadow-sm"
                                            title="ลบคำสั่ง"
                                            aria-label="ลบคำสั่ง {{ $order->order_number }}">
                                            <i class="ri-delete-bin-line text-sm" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="ri-inbox-line text-4xl block mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">ไม่พบข้อมูลคำสั่งในระบบ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
