@extends('layouts.app')

@section('header_title')
    <i class="ri-user-settings-line text-indigo-600 text-lg"></i>
    <span>การจัดการเจ้าหน้าที่ผู้ใช้งาน</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                รายชื่อเจ้าหน้าที่ผู้ใช้งาน
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                กำหนดบทบาท สิทธิ์การเข้าถึง และเปิด/ปิดสถานะการใช้งานของเจ้าหน้าที่
            </p>
        </div>

        <button onclick="openAddModal()" 
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition active:scale-95">
            <i class="ri-user-add-line text-base"></i>
            <span>เพิ่มเจ้าหน้าที่ใหม่</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-emerald-600 text-xl"></i>
            <span class="text-xs font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs text-left">
                <thead class="bg-slate-50/80 text-slate-600 font-bold uppercase border-b border-slate-200/70">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">ชื่อ-นามสกุล / บัญชี</th>
                        <th scope="col" class="px-6 py-3.5">บทบาท (Role)</th>
                        <th scope="col" class="px-6 py-3.5 text-center">สถานะการเข้าใช้งาน</th>
                        <th scope="col" class="px-6 py-3.5 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-xs">{{ $user->name }}</p>
                                        <p class="text-[11px] text-slate-500">@<span>{{ $user->username ?? '-' }}</span></p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role == 'admin')
                                    <span class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg text-[11px] font-bold border border-purple-200">
                                        <i class="ri-shield-star-line mr-1" aria-hidden="true"></i> ผู้ดูแลระบบ (Admin)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[11px] font-bold border border-indigo-200">
                                        <i class="ri-user-3-line mr-1" aria-hidden="true"></i> เจ้าหน้าที่นิติกร (Staff)
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-2.5">
                                    <button type="button" 
                                        onclick="toggleUserStatus('{{ $user->id }}', this)"
                                        class="switch-track {{ $user->is_active ? 'active' : '' }}"
                                        role="switch"
                                        aria-checked="{{ $user->is_active ? 'true' : 'false' }}"
                                        aria-label="เปลี่ยนสถานะผู้ใช้งาน {{ $user->name }}"
                                        title="คลิกเพื่อ{{ $user->is_active ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน' }}">
                                        <span class="switch-thumb">
                                            <i class="ri-loader-4-line animate-spin spinner" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                    <span class="switch-label text-[11px] font-bold select-none text-left w-14 {{ $user->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                        {{ $user->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center gap-1.5">
                                    <button onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')" 
                                        class="w-8 h-8 flex items-center justify-center text-amber-600 hover:bg-amber-50 rounded-xl border border-amber-100 transition shadow-sm"
                                        aria-label="แก้ไขข้อมูล {{ $user->name }}"
                                        title="แก้ไขข้อมูล">
                                        <i class="ri-edit-line text-sm" aria-hidden="true"></i>
                                    </button>

                                    @if($user->id != auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบเจ้าหน้าที่ท่านนี้?')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="w-8 h-8 flex items-center justify-center text-rose-600 hover:bg-rose-50 rounded-xl border border-rose-100 transition shadow-sm"
                                                aria-label="ลบเจ้าหน้าที่ {{ $user->name }}"
                                                title="ลบเจ้าหน้าที่">
                                                <i class="ri-delete-bin-line text-sm" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="userModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all">
    <div class="bg-white rounded-3xl w-full max-w-md p-7 shadow-2xl border border-slate-100 space-y-5 animate-scale-up" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 id="modalTitle" class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="ri-user-add-line text-indigo-600"></i>
                <span>เพิ่มเจ้าหน้าที่ใหม่</span>
            </h3>
            <button onclick="closeModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition" aria-label="ปิด">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <form id="userForm" method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div>
                <label for="userName" class="block text-xs font-bold text-slate-700 mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="userName" required 
                    placeholder="เช่น นายสมชาย ใจดี"
                    class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs transition">
            </div>

            <div id="usernameDiv">
                <label for="username" class="block text-xs font-bold text-slate-700 mb-1">ชื่อผู้ใช้งาน (Username) <span class="text-rose-500">*</span></label>
                <input type="text" name="username" id="username" 
                    placeholder="เช่น somchai"
                    class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs transition">
            </div>

            <div>
                <label for="userRole" class="block text-xs font-bold text-slate-700 mb-1">สิทธิ์การใช้งาน (Role) <span class="text-rose-500">*</span></label>
                <select name="role" id="userRole" required 
                    class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs transition">
                    <option value="staff">Staff / นิติกร (ดูและจัดการสำนวน)</option>
                    <option value="admin">Administrator (จัดการระบบและผู้ใช้)</option>
                </select>
            </div>

            <div>
                <label for="userPassword" class="block text-xs font-bold text-slate-700 mb-1">
                    รหัสผ่าน <span id="passReq" class="text-slate-400 font-normal">(เว้นว่างถ้าไม่เปลี่ยน)</span>
                </label>
                <input type="password" name="password" id="userPassword" placeholder="••••••••"
                    class="w-full h-10 px-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-xs transition">
            </div>

            <div class="mt-6 flex gap-2.5 pt-2">
                <button type="button" onclick="closeModal()" 
                    class="flex-1 h-10 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                    ยกเลิก
                </button>
                <button type="submit" 
                    class="flex-1 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md shadow-indigo-200 transition active:scale-95">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom Toggle Switch */
    .switch-track {
        position: relative;
        display: inline-flex;
        align-items: center;
        width: 44px;
        height: 24px;
        min-width: 44px;
        border-radius: 9999px;
        background-color: #cbd5e1;
        cursor: pointer;
        transition: background-color 0.25s ease, box-shadow 0.2s ease, transform 0.1s ease;
        border: none;
        padding: 0;
        outline: none;
        user-select: none;
        flex-shrink: 0;
    }
    .switch-track:hover {
        opacity: 0.95;
    }
    .switch-track:active {
        transform: scale(0.96);
    }
    .switch-track.active {
        background-color: #10b981; /* emerald-500 */
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
    }
    .switch-track:not(.active) {
        background-color: #cbd5e1; /* slate-300 */
    }
    .switch-thumb {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    .switch-track.active .switch-thumb {
        transform: translateX(20px);
    }
    .switch-track:not(.active) .switch-thumb {
        transform: translateX(0px);
    }
    .switch-thumb .spinner {
        font-size: 10px;
        color: #64748b;
        display: none;
    }
    .switch-track.is-loading .switch-thumb .spinner {
        display: inline-block;
    }
</style>
@endsection

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="ri-user-add-line text-indigo-600"></i> <span>เพิ่มเจ้าหน้าที่ใหม่</span>';
        document.getElementById('formMethod').value = "POST";
        document.getElementById('userForm').action = "{{ route('users.store') }}";
        document.getElementById('usernameDiv').style.display = "block";
        document.getElementById('passReq').style.display = "none";
        document.getElementById('userName').value = "";
        document.getElementById('username').value = "";
        document.getElementById('userRole').value = "staff";
        document.getElementById('userModal').classList.replace('hidden', 'flex');
    }

    function openEditModal(id, name, role) {
        document.getElementById('modalTitle').innerHTML = '<i class="ri-edit-line text-indigo-600"></i> <span>แก้ไขข้อมูลเจ้าหน้าที่</span>';
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('userForm').action = "/setting-user/" + id;
        document.getElementById('userName').value = name;
        document.getElementById('userRole').value = role;
        document.getElementById('usernameDiv').style.display = "none";
        document.getElementById('passReq').style.display = "inline";
        document.getElementById('userModal').classList.replace('hidden', 'flex');
    }

    function closeModal() {
        document.getElementById('userModal').classList.replace('flex', 'hidden');
    }

    async function toggleUserStatus(userId, btn) {
        if (btn.disabled) return;
        
        const label = btn.closest('div').querySelector('.switch-label');
        const wasActive = btn.classList.contains('active');
        const willBeActive = !wasActive;

        // Instant optimistic toggle
        btn.classList.toggle('active', willBeActive);
        btn.classList.add('is-loading');
        btn.setAttribute('aria-checked', willBeActive ? 'true' : 'false');
        btn.title = `คลิกเพื่อ${willBeActive ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน'}`;
        btn.disabled = true;

        if (label) {
            label.textContent = willBeActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
            label.className = `switch-label text-[11px] font-bold select-none text-left w-14 ${willBeActive ? 'text-emerald-600' : 'text-slate-400'}`;
        }

        try {
            const response = await fetch(`/setting-user/${userId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            const serverActive = (data.is_active !== undefined) ? data.is_active : willBeActive;
            
            btn.classList.toggle('active', serverActive);
            btn.setAttribute('aria-checked', serverActive ? 'true' : 'false');
            btn.title = `คลิกเพื่อ${serverActive ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน'}`;
            if (label) {
                label.textContent = serverActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
                label.className = `switch-label text-[11px] font-bold select-none text-left w-14 ${serverActive ? 'text-emerald-600' : 'text-slate-400'}`;
            }
        } catch (error) {
            console.error('Toggle status error:', error);
            // Revert back on error
            btn.classList.toggle('active', wasActive);
            btn.setAttribute('aria-checked', wasActive ? 'true' : 'false');
            btn.title = `คลิกเพื่อ${wasActive ? 'ปิดการใช้งาน' : 'เปิดการใช้งาน'}`;
            if (label) {
                label.textContent = wasActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
                label.className = `switch-label text-[11px] font-bold select-none text-left w-14 ${wasActive ? 'text-emerald-600' : 'text-slate-400'}`;
            }
            alert('เกิดข้อผิดพลาดในการเปลี่ยนสถานะ');
        } finally {
            btn.disabled = false;
            btn.classList.remove('is-loading');
        }
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('userModal');
        if (event.target === modal) {
            closeModal();
        }
    });
</script>
@endpush
