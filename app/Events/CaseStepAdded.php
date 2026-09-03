<?php

namespace App\Events;

use App\Models\LegalCase;
use App\Models\CaseStep;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseStepAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $case;
    public $step;
    public $message;
    public $user;

    public function __construct(LegalCase $case, CaseStep $step)
    {
        $this->case = $case;
        $this->step = $step;
        $this->user = $step->user?->name ?? 'เจ้าหน้าที่';
        $this->message = "อัปเดตความคืบหน้า ครั้งที่ {$step->step_num} สำนวน {$case->case_number} โดย {$this->user}";
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('law-system-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'case.step.added';
    }
}
