<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('settings.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username,',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role, // เพิ่มบรรทัดนี้
            'password' => Hash::make($request->password),
            'is_active' => 1
        ]);

        return back()->with('success', 'เพิ่มเจ้าหน้าที่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->role = $request->role; // เพิ่มบรรทัดนี้

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active; // สลับสถานะ 0 เป็น 1 หรือ 1 เป็น 0
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool)$user->is_active,
                'message' => 'เปลี่ยนสถานะการใช้งานเรียบร้อยแล้ว'
            ]);
        }

        return back()->with('success', 'เปลี่ยนสถานะการใช้งานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบตัวเองได้');
        }
        User::destroy($id);
        return back()->with('success', 'ลบเจ้าหน้าที่เรียบร้อยแล้ว');
    }
}
