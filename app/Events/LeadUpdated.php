<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('organization.' . $this->lead->organization_id);
    }

    public function broadcastWith()
    {
        return [
            'lead' => [
                'id' => $this->lead->id,
                'name' => $this->lead->name,
                'status' => $this->lead->status,
                'updated_at' => $this->lead->updated_at,
            ],
        ];
    }
}
