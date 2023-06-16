<?php

namespace App\Jobs;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingJob implements ShouldBroadcastNow, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo 'event has been handled.';
    }

    /**
     * @return Channel
     */

    public function broadcastOn(): Channel
    {
        return new Channel(2222);
    }

    /**
     * Get the data to broadcast for the model.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return ['title' => "New Announcement"];
    }
}
