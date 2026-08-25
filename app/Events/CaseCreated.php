<?php

namespace App\Events;

use App\Models\LegalCase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $case;
    public $message;
    public $creator;

    public function __construct(LegalCase $case)
    {
        $this->case = $case;
        $this->creator = $case->user?->name ?? 'เจ้าหน้าที่';
        $this->message = "มีสำนวนใหม่: {$case->case_number} ({$case->subject}) โดย {$this->creator}";
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('law-system-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'case.created';
    }
}
