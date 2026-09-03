<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Google2faController extends Controller
{
    public function verify(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'totp_code' => 'required|numeric'
        ], [
            'totp_code.required' => 'กรุณากรอกรหัส 6 หลัก',
            'totp_code.numeric' => 'รหัสต้องเป็นตัวเลขเท่านั้น'
        ]);

        if ($validator->fails()) {
            return back()->withErrors(['auth_error' => $validator->errors()->first('totp_code')]);
        }

        $google2fa = new Google2FA();
        $userId = session('2fa:user_id');
        
        if ($userId) {
            // Flow 1: 2-Step Login (Password -> 2FA)
            $user = User::find($userId);
            if ($user && $user->two_factor_secret) {
                try {
                    if ($google2fa->verifyKey($user->two_factor_secret, $request->totp_code, 4)) {
                        session()->forget('2fa:user_id');
                        Auth::login($user);
                        $request->session()->regenerate();
                        return redirect()->intended('/');
                    }
                } catch (\Exception $e) {}
            }
        } else {
            // Flow 2: Direct 2FA Login
            $users = User::whereNotNull('two_factor_secret')->where('is_active', 1)->get();
            foreach ($users as $user) {
                try {
                    if ($user->two_factor_secret && $google2fa->verifyKey($user->two_factor_secret, $request->totp_code, 4)) {
                        Auth::login($user);
                        $request->session()->regenerate();
                        return redirect()->intended('/');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return back()->withErrors(['auth_error' => 'รหัส 6 หลักไม่ถูกต้อง หรือไม่มีในระบบ']);
    }

    public function challenge()
    {
        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect('/login');
        }

        return view('auth.google2fa_challenge', compact('user'));
    }

    public function generateSetupQr(Request $request)
    {
        $username = $request->query('user', 'admin');
        $user = User::where('username', $username)->orWhere('email', $username)->first();
        
        if (!$user) {
            $user = User::where('username', 'admin')->first();
        }
        
        if (!Auth::check() || Auth::user()->id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $google2fa = new Google2FA();
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();
        }
        
        $qrCodeUrl = $google2fa->getQRCodeUrl('ODPC10 LSS', $user->username . ' (' . $user->email . ')', $user->two_factor_secret);
        
        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);
        $base64 = base64_encode($svg);
        
        return response()->json(['qr_url' => 'data:image/svg+xml;base64,' . $base64]);
    }

    public function setup($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        if (!Auth::check() || Auth::user()->username !== $username) {
            abort(403, 'Unauthorized action.');
        }
        
        $google2fa = new Google2FA();
        
        $secret = session('temp_2fa_secret');
        if (!$secret) {
            $secret = $google2fa->generateSecretKey();
            session(['temp_2fa_secret' => $secret]);
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'ODPC10 LSS',
            $user->username,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);
        $googleChartsUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);

        return view('auth.google2fa_setup', compact('user', 'googleChartsUrl'));
    }

    public function confirmSetup(Request $request)
    {
        $request->validate([
            'totp_code' => 'required|numeric'
        ], [
            'totp_code.required' => 'กรุณากรอกรหัส 6 หลัก',
            'totp_code.numeric' => 'รหัสต้องเป็นตัวเลขเท่านั้น'
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->withErrors(['totp_code' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        
        $secret = session('temp_2fa_secret');

        if (!$secret || !$user) {
            return redirect()->back()->withErrors(['totp_code' => 'เกิดข้อผิดพลาด กรุณาโหลดหน้าใหม่']);
        }

        $google2fa = new Google2FA();
        // เพิ่ม window เป็น 4 (ยอมรับความคลาดเคลื่อนของเวลาได้ 2 นาที) เผื่อเวลา Server หรือมือถือเดินไม่ตรงกัน
        $valid = $google2fa->verifyKey($secret, $request->totp_code, 4);

        if ($valid) {
            $user->two_factor_secret = $secret;
            $user->save();
            session()->forget('temp_2fa_secret');
            
            return redirect()->route('dashboard')->with('success', 'เปิดใช้งาน 2FA สำเร็จ');
        }

        return redirect()->back()->withErrors(['totp_code' => 'รหัสไม่ถูกต้อง กรุณาลองใหม่']);
    }
}
