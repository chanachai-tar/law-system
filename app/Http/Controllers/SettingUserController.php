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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:150|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,officer,staff'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->is_active = 1;
        $user->save();

        return back()->with('success', 'เพิ่มเจ้าหน้าที่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,officer,staff',
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

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
