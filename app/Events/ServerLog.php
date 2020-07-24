<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;

/**
 * Class onServerLog
 * @package App\Events
 */
class ServerLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $logs;
    public $emailSend;

	/**
	 * Create a new event instance.
	 *
	 * @param Model $logs
	 * @param bool $emailSend - метка для отправки емаил
	 */
    public function __construct(Model $logs, $emailSend = false)
    {
        $this->logs = $logs;
        $this->emailSend = $emailSend;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
