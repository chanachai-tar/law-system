<?php

namespace App\Events;

use App\Models\AppointmentOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $message;

    public function __construct(AppointmentOrder $order)
    {
        $this->order = $order;
        $this->message = "มีคำสั่งแต่งตั้งใหม่: {$order->order_number} ({$order->subject})";
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('law-system-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
