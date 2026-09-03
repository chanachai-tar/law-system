<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SystemSetting;
use App\Services\TelegramService;

class SettingTelegramController extends Controller
{
    /**
     * หน้าจอตั้งค่าการแจ้งเตือน Telegram (สำหรับ Admin)
     */
    public function index()
    {
        // ตรวจสอบสิทธิ์ Admin
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'สงวนสิทธิ์เฉพาะผู้ดูแลระบบเท่านั้น');
        }

        $botToken = SystemSetting::get('telegram_bot_token', env('TELEGRAM_BOT_TOKEN', ''));
        $chatId   = SystemSetting::get('telegram_chat_id', env('TELEGRAM_CHAT_ID', ''));
        $groupName = SystemSetting::get('telegram_group_name', 'ODPC10-LSS');

        $notifyCaseCreated = SystemSetting::get('telegram_notify_case_created', '1') == '1';
        $notifyStepAdded   = SystemSetting::get('telegram_notify_step_added', '1') == '1';
        $notifyCaseClosed  = SystemSetting::get('telegram_notify_case_closed', '1') == '1';

        // ตรวจสอบสถานะการเชื่อมต่อบอท
        $botInfo = null;
        $isConnected = false;

        if (!empty($botToken)) {
            try {
                $res = Http::timeout(4)
                    ->get("https://api.telegram.org/bot{$botToken}/getMe");

                if ($res->successful() && $res->json('ok')) {
                    $botInfo = $res->json('result');
                    $isConnected = true;
                }
            } catch (\Throwable $e) {
                // Connection check error ignored
            }
        }

        return view('settings.telegram.index', compact(
            'botToken',
            'chatId',
            'groupName',
            'notifyCaseCreated',
            'notifyStepAdded',
            'notifyCaseClosed',
            'botInfo',
            'isConnected'
        ));
    }

    /**
     * บันทึกการตั้งค่า Telegram
     */
    public function update(Request $request)
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'telegram_bot_token'  => 'nullable|string|max:255',
            'telegram_chat_id'    => 'nullable|string|max:255',
            'telegram_group_name' => 'required|string|max:100',
            'telegram_qr_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        SystemSetting::set('telegram_bot_token', trim($request->telegram_bot_token ?? ''));
        SystemSetting::set('telegram_chat_id', trim($request->telegram_chat_id ?? ''));
        SystemSetting::set('telegram_group_name', trim($request->telegram_group_name ?? 'ODPC10-LSS'));

        SystemSetting::set('telegram_notify_case_created', $request->has('notify_case_created') ? '1' : '0');
        SystemSetting::set('telegram_notify_step_added', $request->has('notify_step_added') ? '1' : '0');
        SystemSetting::set('telegram_notify_case_closed', $request->has('notify_case_closed') ? '1' : '0');

        // อัปโหลดรูป QR Code ใหม่หากมี
        if ($request->hasFile('telegram_qr_image')) {
            $qrFile = $request->file('telegram_qr_image');
            $qrFile->move(public_path('images'), 'telegram_qr.png');
        }

        return redirect()->route('settings.telegram.index')->with('success', 'บันทึกการตั้งค่าการแจ้งเตือน Telegram เรียบร้อยแล้ว');
    }

    /**
     * ทดสอบส่งข้อความเข้ากลุ่ม Telegram
     */
    public function testNotification(Request $request)
    {
        if (Auth::user()?->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'ไม่มีสิทธิ์ดำเนินการ'], 403);
        }

        $groupName = SystemSetting::get('telegram_group_name', 'ODPC10-LSS');
        $now = thaidate(now(), 'full');

        $msg = "🔔 <b>[ทดสอบการเชื่อมต่อ Telegram Bot]</b>\n";
        $msg .= "🏛️ สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี\n";
        $msg .= "📱 <b>กลุ่ม:</b> {$groupName}\n\n";
        $msg .= "✅ การเชื่อมต่อระบบงานสำนวนกฎหมาย (ODPC10-LSS) และกลุ่ม Telegram สำเร็จสมบูรณ์!\n";
        $msg .= "👤 <b>ผู้ทดสอบ:</b> " . (Auth::user()->name ?? 'ผู้ดูแลระบบ') . "\n";
        $msg .= "🕒 <b>เวลา:</b> {$now}";

        $result = TelegramService::sendMessage($msg);

        return response()->json($result);
    }
}
