@extends('layouts.app')

@section('header_title')
    <i class="ri-shield-star-fill text-rose-600 text-lg"></i>
    <span>การจัดการสำหรับ Super Admin</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                แผงควบคุมหลักสำหรับผู้ดูแลระบบสูงสุด
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                ดูข้อมูลเชิงลึกและตั้งค่าขั้นสูงของระบบ
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-2xl mb-3">
                <i class="ri-database-2-line"></i>
            </div>
            <h3 class="font-bold text-slate-800">จัดการระบบฐานข้อมูล</h3>
            <p class="text-xs text-slate-500 mt-1">เครื่องมือตั้งค่าและตรวจสอบข้อมูล</p>
            <button class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition" disabled>
                อยู่ระหว่างพัฒนา
            </button>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-3">
                <i class="ri-history-line"></i>
            </div>
            <h3 class="font-bold text-slate-800">System Logs</h3>
            <p class="text-xs text-slate-500 mt-1">ประวัติการใช้งานและข้อผิดพลาด</p>
            <button class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition" disabled>
                อยู่ระหว่างพัฒนา
            </button>
        </div>
        
        <!-- Card 3 -->
        <a href="{{ route('users.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col items-center text-center hover:border-indigo-300 hover:shadow-md transition">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-3">
                <i class="ri-group-line"></i>
            </div>
            <h3 class="font-bold text-slate-800">จัดการผู้ใช้งานทั้งหมด</h3>
            <p class="text-xs text-slate-500 mt-1">ตั้งค่าและมอบหมายสิทธิ์</p>
            <div class="mt-4 text-xs font-semibold text-indigo-600">
                ไปที่หน้าจัดการ &rarr;
            </div>
        </a>
    </div>

</div>
@endsection
