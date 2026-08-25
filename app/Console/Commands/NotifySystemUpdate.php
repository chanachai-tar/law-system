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
    protected $description = 'ส่งข้อความแจ้งเตือนการอัปเดต Git ไปยังห้อง [Updated on GitHub] พร้อมปุ่มลิงก์ดูการเปลี่ยนแปลง';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version   = $this->option('ver') ?: '1.0.0';
        $updateMsg = $this->argument('message');

        if (empty($updateMsg)) {
            $updateMsg = 'อัปเดตและปรับปรุงประสิทธิภาพระบบงาน';
        }

        // ดึงข้อมูล Git แบบ Dynamic
        $branch     = trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>nul') ?: 'main');
        $author     = trim(shell_exec('git log -1 --pretty=format:"%an" 2>nul') ?: 'chanachai');
        $commit     = trim(shell_exec('git log -1 --pretty=format:"%h" 2>nul') ?: 'latest');
        $repoRemote = trim(shell_exec('git config --get remote.origin.url 2>nul') ?: '');
        
        $repoName = 'chanachai-tar/law-system';
        if (!empty($repoRemote) && preg_match('/github\.com[\/:](.+?)(?:\.git)?$/i', $repoRemote, $matches)) {
            $repoName = $matches[1];
        }

        $repoUrl   = "https://github.com/{$repoName}";
        $commitUrl = "https://github.com/{$repoName}/commit/{$commit}";

        // ชื่อย่อระบบแบบ Dynamic (เช่น LSS, OPD, LAB)
        $shortName  = env('APP_SHORT_NAME') ?: (str_contains(strtolower($repoName), 'law') ? 'LSS' : (str_contains(strtolower($repoName), 'opd') ? 'OPD' : strtoupper(basename($repoName))));
        $systemName = config('app.name', 'ระบบงานสารบรรณและทะเบียนสำนวนกฎหมาย (ODPC10-LSS)');

        $msg = "🚀 <b>[{$shortName} Updated on GitHub]</b>\n";
        $msg .= "🏛️ <b>ระบบ :</b> {$systemName}\n\n";
        $msg .= "🏷️ <b>Version:</b> <code>v{$version}</code>\n";
        $msg .= "🌿 <b>Branch:</b> <code>{$branch}</code>\n";
        $msg .= "👤 <b>Author:</b> {$author}\n";
        $msg .= "📌 <b>Commit:</b> <code>{$commit}</code>\n";
        $msg .= "📝 <b>Message:</b> {$updateMsg}\n";
        $msg .= "🔗 <b>Repository:</b> <a href=\"{$repoUrl}\">{$repoName}</a>\n\n";
        $msg .= "✅ <b>Status:</b> Pushed to repository successfully.";

        $gitBotToken = config('services.telegram.git_bot_token') ?: env('GIT_TELEGRAM_BOT_TOKEN');
        $gitChatId   = config('services.telegram.git_chat_id') ?: env('GIT_TELEGRAM_CHAT_ID');

        if (empty($gitBotToken) || empty($gitChatId)) {
            $this->warn("ไม่ได้ตั้งค่า GIT_TELEGRAM_BOT_TOKEN หรือ GIT_TELEGRAM_CHAT_ID ใน .env");
            return Command::SUCCESS;
        }

        $this->info("กำลังส่งข้อความแจ้งเตือนไปยังห้อง [Updated on GitHub]...");

        $replyMarkup = [
            'inline_keyboard' => [
                [
                    [
                        'text' => '🔍 ดูการเปลี่ยนแปลงบน GitHub ↗️',
                        'url'  => $commitUrl,
                    ],
                    [
                        'text' => '📁 ไปที่ Repository ↗️',
                        'url'  => $repoUrl,
                    ],
                ]
            ]
        ];

        $result = TelegramService::sendMessage($msg, $gitChatId, $gitBotToken, $replyMarkup);

        if ($result['success'] ?? false) {
            $this->info("✅ " . ($result['message'] ?? 'ส่งข้อความแจ้งเตือนสำเร็จ!'));
            return Command::SUCCESS;
        } else {
            $this->error("❌ " . ($result['message'] ?? 'เกิดข้อผิดพลาดในการส่งข้อความ'));
            return Command::FAILURE;
        }
    }
}
