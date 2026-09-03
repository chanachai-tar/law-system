<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SsoController extends Controller
{
    /**
     * Redirect the user to the ODPC10 IDP authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('oidc')->redirect();
    }

    /**
     * Obtain the user information from the ODPC10 IDP and log them in.
     */
    public function callback()
    {
        try {
            $oidcUser = Socialite::driver('oidc')->user();

            $email = $oidcUser->email;
            $nickname = $oidcUser->nickname ?? $oidcUser->id;
            $name = $oidcUser->name ?? $nickname;

            // ค้นหาผู้ใช้งานจาก Email, Username
            $user = User::where(function ($query) use ($email, $nickname) {
                if (!empty($email)) {
                    $query->where('email', $email);
                }
                if (!empty($nickname)) {
                    $query->orWhere('username', $nickname);
                }
            })->first();

            // หากยังไม่มีผู้ใช้งานในระบบ ให้สร้างบัญชีให้อัตโนมัติ (Role: officer)
            if (!$user) {
                $username = !empty($nickname) ? $nickname : (!empty($email) ? explode('@', $email)[0] : 'user_' . Str::random(6));
                
                // ตรวจสอบความซ้ำของ username
                if (User::where('username', $username)->exists()) {
                    $username .= '_' . rand(10, 99);
                }

                $user = User::create([
                    'name'      => $name ?: 'ผู้ใช้งาน SSO',
                    'email'     => $email ?: ($username . '@odpc10.local'),
                    'username'  => $username,
                    'password'  => Hash::make(Str::random(24)),
                    'role'      => 'officer',
                    'is_active' => true,
                ]);
            }

            // ตรวจสอบว่าบัญชีถูกระงับการใช้งานหรือไม่
            if (isset($user->is_active) && !$user->is_active) {
                return redirect()->route('login')->withErrors(['username' => 'บัญชีผู้ใช้งานนี้ถูกระงับการใช้งานชั่วคราว กรุณาติดต่อผู้ดูแลระบบ']);
            }

            // เข้าสู่ระบบ
            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (\Throwable $e) {
            Log::error('SSO Callback Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['username' => 'เกิดข้อผิดพลาดในการเชื่อมต่อเข้าสู่ระบบ SSO']);
        }
    }
}
