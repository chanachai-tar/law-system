<?php

namespace App\Events;

use App\Models\LegalCase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseClosed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $case;
    public $message;

    public function __construct(LegalCase $case)
    {
        $this->case = $case;
        $this->message = "ปิดสำนวนเรียบร้อยแล้ว: {$case->case_number}";
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('law-system-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'case.closed';
    }
}
