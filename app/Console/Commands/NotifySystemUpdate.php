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
    protected $description = 'ส่งข้อความแจ้งเตือนการอัปเดต Git ไปยังห้อง [Updated on GitHub]';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version   = $this->option('ver') ?: '1.0.0';
        $updateMsg = $this->argument('message');

        if (empty($updateMsg)) {
            $updateMsg = 'อัปเดตและปรับปรุงระบบงานสำนวนกฎหมาย (law-system) บน GitHub อัตโนมัติ';
        }

        $now = thaidate(now(), 'full');
        $appUrl = config('app.url', 'http://192.168.13.71:8000');

        $msg = "🚀 <b>[Updated on GitHub]</b>\n";
        $msg .= "🌿 <b>ระบบ:</b> law-system (ODPC10-LSS)\n";
        $msg .= "🏛️ สำนักงานป้องกันควบคุมโรคที่ 10 จังหวัดอุบลราชธานี\n\n";
        $msg .= "📦 <b>เวอร์ชัน:</b> <code>v{$version}</code>\n";
        $msg .= "📝 <b>สิ่งที่ได้รับการแก้ไข:</b>\n";
        $msg .= "👉 <i>{$updateMsg}</i>\n\n";
        $msg .= "🕒 <b>เวลาอัปเดต:</b> {$now}\n";
        $msg .= "🔗 <b>URL:</b> {$appUrl}";

        $gitBotToken = config('services.telegram.git_bot_token') ?: env('GIT_TELEGRAM_BOT_TOKEN');
        $gitChatId   = config('services.telegram.git_chat_id') ?: env('GIT_TELEGRAM_CHAT_ID');

        if (empty($gitBotToken) || empty($gitChatId)) {
            $this->warn("ไม่ได้ตั้งค่า GIT_TELEGRAM_BOT_TOKEN หรือ GIT_TELEGRAM_CHAT_ID ใน .env สำหรับห้อง Updated on GitHub");
            return Command::SUCCESS;
        }

        $this->info("กำลังส่งข้อความแจ้งเตือนไปยังห้อง [Updated on GitHub]...");

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
