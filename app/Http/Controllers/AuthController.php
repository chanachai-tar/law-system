<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'password.required' => 'กรุณากรอกรหัสผ่าน'
        ]);
        
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->two_factor_secret) {
                Auth::logout();
                $request->session()->put('2fa:user_id', $user->id);
                return redirect()->route('google2fa.challenge');
            } else {
                $request->session()->regenerate();
                return redirect()->route('google2fa.setup', ['username' => $user->username]);
            }
        }

        return back()->withErrors(['username' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
