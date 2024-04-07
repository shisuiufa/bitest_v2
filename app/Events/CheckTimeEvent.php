<?php

namespace App\Events;

use App\Models\TestUser;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckTimeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private TestUser $testUser;

    /**
     * Create a new event instance.
     */
    public function __construct(TestUser $testUser)
    {
        $this->testUser = $testUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('check-time'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'check-time';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $test = $this->testUser->test();
        $startTime = $this->testUser->created_at;
        $endTime = Carbon::createFromTime($startTime)->addSeconds($test->time_complete);

        return [
            'startTime' => $this->testUser->created_at,
            'endTime' => $endTime->sub(now())->seconds(),
        ];
    }

}
