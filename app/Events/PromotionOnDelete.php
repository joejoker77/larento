<?php

namespace App\Events;


use Illuminate\Queue\SerializesModels;
use App\Entities\Site\Promotions\Promotion;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PromotionOnDelete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Promotion $promotion;

    /**
     * Create a new event instance.
     */
    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
