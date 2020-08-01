<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrivateChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * POST data
	 * 
	 * @var array
	 */
	public $data;

	/**
	 * Create a new event instance.
	 *
	 * @param $data
	 */
    public function __construct($data)
    {
        $this->data = $data;
        ChatMessage::create(["room_id" => $this->data["room_id"], "message" => $this->data["body"]]);
		$this->dontBroadcastToCurrentUser(); // The message will be not duplicated
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('room.'.$this->data["room_id"]);
    }
}
