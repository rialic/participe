<?php

namespace App\Observers;

use App\Jobs\WebClassJob;
use App\Models\Event;
use App\Repository\UserRepository;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class EventObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ){}

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        WebClassJob::dispatch($event, $this->userRepository)->delay(now()->addSeconds(5));
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // TODO REALIZAR O ENVIO DE EMAIL CASO O TEMA A DATA TENHA SIDO ALTERADOS
    }
}
