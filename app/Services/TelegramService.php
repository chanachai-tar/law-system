<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\LegalCase;
use App\Models\CaseStep;
use App\Models\SystemSetting;

class TelegramService
{
    public static function getBotToken(): ?string
    {
        return SystemSetting::get('telegram_bot_token', env('TELEGRAM_BOT_TOKEN'));
    }

    public static function getChatId(): ?string
    {
        return SystemSetting::get('telegram_chat_id', env('TELEGRAM_CHAT_ID'));
    }

    public static function getGroupName(): string
    {
        return SystemSetting::get('telegram_group_name', 'ODPC10-LSS');
    }

    public static function isNotificationEnabled(string $event): bool
    {
        $enabled = SystemSetting::get('telegram_notify_' . $event, '1');
        return $enabled === '1' || $enabled === 'true' || $enabled === true;
    }

    /**
     * Send a raw text message to Telegram Bot / Group
     */
    public static function sendMessage(string $message, ?string $chatId = null): array
    {
        $botToken = self::getBotToken();
        $targetChatId = $chatId ?: self::getChatId();

        if (empty($botToken)) {
            return ['success' => false, 'message' => 'ยังไม่ได้ตั้งค่า Telegram Bot Token ในระบบ'];
        }

        if (empty($targetChatId)) {
            return ['success' => false, 'message' => 'ยังไม่ได้ตั้งค่า Telegram Chat ID / Group ID ในระบบ'];
        }

        try {
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $response = Http::withOptions(['verify' => false])
                ->timeout(6)
                ->post($url, [
                    'chat_id'                  => $targetChatId,
                    'text'                     => $message,
                    'parse_mode'               => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'ส่งข้อความแจ้งเตือนเข้า Telegram สำเร็จ!'];
            } else {
                $err = $response->json('description') ?? ('HTTP ' . $response->status());
                return ['success' => false, 'message' => 'Telegram ปฏิเสธคำขอ: ' . $err];
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send Telegram notification: " . $e->getMessage());
            return ['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อ Telegram API ได้: ' . $e->getMessage()];
        }
    }

    /**
     * แจ้งเตือนเมื่อมีการเปิดสำนวนคดีใหม่
     */
    public static function notifyCaseCreated(LegalCase $case): bool
    {
        if (!self::isNotificationEnabled('case_created')) {
            return false;
        }

        $lawType = law_type($case->law_type);
        $creator = $case->user->name ?? auth()->user()?->name ?? 'เจ้าหน้าที่';
        $due = $case->due_date ? thaidate($case->due_date, 'short') : 'ไม่ระบุ';
        $groupName = self::getGroupName();

        $msg = "⚖️ <b>[เปิดสำนวนใหม่] {$groupName}</b>\n";
        $msg .= "🏛️ สคร.10 อุบลราชธานี\n\n";
        $msg .= "📌 <b>เลขที่สำนวน:</b> <code>{$case->case_number}</code>\n";
        $msg .= "📂 <b>ประเภท:</b> {$lawType}\n";
        $msg .= "📝 <b>เรื่อง:</b> {$case->subject}\n";
        $msg .= "⏳ <b>กรอบเวลา (SLA):</b> {$due}\n";
        $msg .= "👤 <b>ผู้ดำเนินการ:</b> {$creator}\n";
        $msg .= "🕒 <b>เวลา:</b> " . thaidate(now(), 'full');

        $result = self::sendMessage($msg);
        return $result['success'] ?? false;
    }

    /**
     * แจ้งเตือนเมื่อมีการบันทึกขั้นตอน / บันทึกความคืบหน้า (ครั้งที่...)
     */
    public static function notifyStepAdded(LegalCase $case, CaseStep $step): bool
    {
        if (!self::isNotificationEnabled('step_added')) {
            return false;
        }

        $lawType = law_type($case->law_type);
        $stepDate = $step->step_date ? thaidate($step->step_date, 'short') : thaidate(now(), 'short');
        $groupName = self::getGroupName();
        $operator = $step->user->name ?? auth()->user()?->name ?? $case->user->name ?? 'เจ้าหน้าที่';

        $msg = "📋 <b>[บันทึกความคืบหน้าสำนวน] {$groupName}</b>\n";
        $msg .= "🏛️ สคร.10 อุบลราชธานี\n\n";
        $msg .= "📌 <b>เลขที่สำนวน:</b> <code>{$case->case_number}</code>\n";
        $msg .= "📂 <b>ประเภท:</b> {$lawType}\n";
        $msg .= "📝 <b>เรื่อง:</b> {$case->subject}\n";
        $msg .= "🔄 <b>ความคืบหน้า ครั้งที่ {$step->step_num}:</b>\n";
        $msg .= "👉 <i>" . strip_tags($step->description) . "</i>\n";
        $msg .= "📅 <b>วันที่ดำเนินการ:</b> {$stepDate}\n";
        $msg .= "👤 <b>ผู้ดำเนินการ:</b> {$operator}\n";
        $msg .= "🕒 <b>เวลาบันทึก:</b> " . thaidate(now(), 'full');

        $result = self::sendMessage($msg);
        return $result['success'] ?? false;
    }

    /**
     * แจ้งเตือนเมื่อปิดสำนวนคดีแล้วเสร็จ
     */
    public static function notifyCaseClosed(LegalCase $case): bool
    {
        if (!self::isNotificationEnabled('case_closed')) {
            return false;
        }

        $lawType = law_type($case->law_type);
        $penalty = $case->penalty_type ?: 'ยุติเรื่อง / ไม่พบความผิด';
        $groupName = self::getGroupName();
        $closer = auth()->user()?->name ?? $case->user->name ?? 'เจ้าหน้าที่';

        $msg = "✅ <b>[ปิดสำนวนเสร็จสิ้น] {$groupName}</b>\n";
        $msg .= "🏛️ สคร.10 อุบลราชธานี\n\n";
        $msg .= "📌 <b>เลขที่สำนวน:</b> <code>{$case->case_number}</code>\n";
        $msg .= "📂 <b>ประเภท:</b> {$lawType}\n";
        $msg .= "📝 <b>เรื่อง:</b> {$case->subject}\n";
        $msg .= "🎯 <b>ผลการวินิจฉัย/โทษ:</b> {$penalty}\n";
        if ($case->damage_amount > 0) {
            $msg .= "💰 <b>ยอดเงินชดใช้/เสียหาย:</b> " . number_format($case->damage_amount, 2) . " บาท\n";
        }
        $msg .= "👤 <b>ผู้ดำเนินการ:</b> {$closer}\n";
        $msg .= "🕒 <b>เวลาปิดสำนวน:</b> " . thaidate(now(), 'full');

        $result = self::sendMessage($msg);
        return $result['success'] ?? false;
    }

    /**
     * แจ้งเตือนเมื่อมีการออกคำสั่งแต่งตั้งคณะกรรมการใหม่
     */
    public static function notifyOrderCreated(\App\Models\AppointmentOrder $order): bool
    {
        $groupName = self::getGroupName();
        $operator = auth()->user()?->name ?? 'เจ้าหน้าที่';
        $orderDate = $order->order_date ? thaidate($order->order_date, 'short') : '-';

        $msg = "📜 <b>[ออกคำสั่งแต่งตั้งใหม่] {$groupName}</b>\n";
        $msg .= "🏛️ สคร.10 อุบลราชธานี\n\n";
        $msg .= "📌 <b>เลขที่คำสั่ง:</b> <code>{$order->order_number}</code>\n";
        $msg .= "📝 <b>เรื่อง:</b> {$order->subject}\n";
        if ($order->to) {
            $msg .= "👥 <b>ถึง/ผู้เกี่ยวข้อง:</b> {$order->to}\n";
        }
        $msg .= "📅 <b>วันที่ลงนามคำสั่ง:</b> {$orderDate}\n";
        $msg .= "👤 <b>ผู้ดำเนินการ:</b> {$operator}\n";
        $msg .= "🕒 <b>เวลาบันทึก:</b> " . thaidate(now(), 'full');

        $result = self::sendMessage($msg);
        return $result['success'] ?? false;
    }
}
