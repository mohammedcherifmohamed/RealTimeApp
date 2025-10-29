<?php 
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $typerId;
    public $receiverId;

    public function __construct($typerId)
    {
        $this->typerId = $typerId;
    }

    public function broadcastOn(): array
    {
        // Broadcast to **the receiver's channel**
        return [
            new PrivateChannel("typing.{$this->typerId}")
        ];
    }

   

    
}
