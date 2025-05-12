<?php

namespace App\Observers;

use App\Jobs\WebClassJob;
use App\Models\Event;
use App\Repository\UserRepository;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class EventObserver implements ShouldHandleEventsAfterCommit
{
    private const NOTIFICATION_KEYS = [
        'type_notification',
        'select_group_emails',
        'cities_to_notify'
    ];

    private const DISPATCH_DELAY = 5;

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function created(Event $event): void
    {
        $this->dispatchWebClassJob($event);
    }

    public function updated(Event $event): void
    {
        if (!is_null($event->deleted_at)) {
            $this->dispatchWebClassJob($event);
            return;
        }

        $originalEvent = app("original_event_{$event->id}");
        $eventChanges = $event->getChanges();
        $updatedVars = $this->collectUpdatedVariables($event, $originalEvent);
        $modelChanged = !empty($updatedVars);
        $changedNotificationKeys = array_intersect(array_keys($eventChanges), self::NOTIFICATION_KEYS);

        app()->forgetInstance("original_event_{$event->id}");

        if (empty($changedNotificationKeys)) {
            if ($modelChanged) {
                $this->dispatchWebClassJob($event, $modelChanged, $updatedVars);
            }
            return;
        }

        if (in_array('type_notification', $changedNotificationKeys)) {
            $this->dispatchWebClassJob($event, $modelChanged, $updatedVars);
            return;
        }

        if (in_array('select_group_emails', $changedNotificationKeys)) {
            $this->handleListChanges(
                $event,
                $originalEvent,
                $modelChanged,
                $updatedVars,
                'select_group_emails',
                'emails'
            );
            return;
        }

        if (in_array('cities_to_notify', $changedNotificationKeys)) {
            $this->handleListChanges(
                $event,
                $originalEvent,
                $modelChanged,
                $updatedVars,
                'cities_to_notify',
                'cities'
            );
            return;
        }
    }

    private function collectUpdatedVariables(Event $event, Event $originalEvent): array
    {
        $updatedVars = [];

        if ($event->wasChanged('name')) {
            $updatedVars['name_attrs'] = $this->defineVariableChanges('name', $originalEvent, $event);
        }

        if ($event->wasChanged('start_at')) {
            $updatedVars['start_at_attrs'] = $this->defineVariableChanges('start_at', $originalEvent, $event);
        }

        return $updatedVars;
    }

    private function defineVariableChanges(string $fieldName, Event $originalEvent, Event $event): array
    {
        if ($fieldName === 'name') {
            return [
                "original_{$fieldName}" => $originalEvent->getAttributes()[$fieldName],
                "updated_{$fieldName}" => $event->getAttributes()[$fieldName]
            ];
        }

        if ($fieldName === 'start_at') {
            return [
                "original_{$fieldName}" => $originalEvent->start_at_datetime_formatted,
                "updated_{$fieldName}" => $event->start_at_datetime_formatted
            ];
        }

        return [];
    }

    private function handleListChanges(
        Event $event,
        Event $originalEvent,
        bool $modelChanged,
        array $updatedVars,
        string $attributeName,
        string $updatedVarKey
    ): void {
        $originalItems = json_decode($originalEvent->getAttributes()[$attributeName] ?? '[]');
        $currentItems = json_decode($event->getAttributes()[$attributeName] ?? '[]');

        $newItems = array_diff($currentItems, $originalItems);

        if (empty($newItems)) {
            if ($modelChanged) {
                $this->dispatchWebClassJob($event, $modelChanged, $updatedVars);
            }
            return;
        }

        $oldItems = array_diff($currentItems, $newItems);
        $updatedVars[$updatedVarKey] = $oldItems;

        if ($modelChanged) {
            $this->dispatchWebClassJob($event, $modelChanged, $updatedVars);
        }

        if (count($currentItems) >= count($originalItems)) {
            $updatedVars[$updatedVarKey] = $newItems;
            $this->dispatchWebClassJob($event, false, $updatedVars);
        }
    }

    private function dispatchWebClassJob(Event $event, bool $modelChanged = false, array $updatedVars = []): void
    {
        WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)
            ->delay(now()->addSeconds(self::DISPATCH_DELAY));
    }
}