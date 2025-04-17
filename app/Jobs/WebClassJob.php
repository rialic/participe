<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WebClassJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Event $event
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dd('Here => ', $this->event);
    }
}
