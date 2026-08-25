<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class NotifySystemUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-update {message? : รายละเอียดการอัปเดตระบบ} {--ver=1.0.0 : เวอร์ชั่นระบบ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ส่งข้อความแจ้งเตือนการอัปเดตระบบไปยังกลุ่ม Telegram (ODPC10-LSS)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $groupName = TelegramService::getGroupName();
        $version   = $this->option('ver') ?: '1.0.0';
        $updateMsg = $this->argument('message');

        if (empty($updateMsg)) {
            $updateMsg = 'อัปเดตและปรับปรุงระบบงานสำนวนกฎหมาย สคร.10 อัตโนมัติ';
        }

        $now = thaidate(now(), 'full');
        $appUrl = config('app.url', 'http://192.168.13.71:8000');

        $msg = "🚀 <b>[แจ้งเตือนการอัปเดตระบบอัตโนมัติ] {$groupName}</b>\n";
        $msg .= "🏛️ สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี\n\n";
        $msg .= "📦 <b>เวอร์ชัน:</b> <code>v{$version}</code>\n";
        $msg .= "📝 <b>สิ่งที่ได้รับการแก้ไข (Auto Summary):</b>\n";
        $msg .= "👉 <i>{$updateMsg}</i>\n\n";
        $msg .= "🕒 <b>เวลาอัปเดต:</b> {$now}\n";
        $msg .= "🔗 <b>เข้าสู่ระบบ:</b> {$appUrl}";

        $this->info("กำลังส่งข้อความแจ้งเตือนไปยังกลุ่ม Telegram [{$groupName}]...");

        $gitBotToken = env('GIT_TELEGRAM_BOT_TOKEN', '8807181650:AAG1iN8jAZIPCI8Nro-KAnB1ieviH2fQhcg');
        $gitChatId   = env('GIT_TELEGRAM_CHAT_ID', '-5531101792');

        $result = TelegramService::sendMessage($msg, $gitChatId, $gitBotToken);

        if ($result['success'] ?? false) {
            $this->info("✅ " . ($result['message'] ?? 'ส่งข้อความแจ้งเตือนสำเร็จ!'));
            return Command::SUCCESS;
        } else {
            $this->error("❌ " . ($result['message'] ?? 'เกิดข้อผิดพลาดในการส่งข้อความ'));
            return Command::FAILURE;
        }
    }
}
