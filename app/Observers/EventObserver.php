<?php

namespace App\Observers;

use App\Jobs\WebClassJob;
use App\Models\Event;
use App\Repository\UserRepository;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class EventObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ){}

    public function created(Event $event): void
    {
        WebClassJob::dispatch($event, $this->userRepository)->delay(now()->addSeconds(5));
    }

    public function updated(Event $event): void
    {
        $originalEvent = app("original_event_{$event->id}");
        $eventChanges = $event->getChanges();
        $observedKeyList = ['type_notification', 'select_group_emails', 'cities_to_notify'];
        $hasTypeNotificationChanges = count(array_intersect(array_keys($eventChanges), $observedKeyList));
        $updatedVars = [];

        app()->forgetInstance("original_event_{$event->id}");

        if ($event->wasChanged('name')) {
            $updatedVars['name_attrs'] = $this->defineNameAndStartAtVariables('name', $originalEvent, $event);
        }

        if ($event->wasChanged('start_at')) {
            $updatedVars['start_at_attrs'] = $this->defineNameAndStartAtVariables('start_at', $originalEvent, $event);
        }

        $modelChanged = !!count($updatedVars);

        if ($hasTypeNotificationChanges) {
            $typeNotification = in_array('type_notification', array_keys($eventChanges));
            $selectGroupEmails = in_array('select_group_emails', array_keys($eventChanges));
            $citiesToNotify = in_array('cities_to_notify', array_keys($eventChanges));

            if($typeNotification) {
                WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));

                return;
            }

            if($selectGroupEmails) {
                $originalEventEmails = json_decode($originalEvent->getAttributes()['select_group_emails']);
                $eventEmails = json_decode($event->getAttributes()['select_group_emails']);
                $newEmails = array_diff($eventEmails, $originalEventEmails);

                if (count($newEmails)) {
                    $oldEmails = array_diff($eventEmails, $newEmails);
                    $updatedVars['emails'] = $oldEmails;

                    if ($modelChanged) {
                        WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));
                    }

                    if (count($eventEmails) >= count($originalEventEmails)) {
                        $updatedVars['emails'] = $newEmails;

                        WebClassJob::dispatch($event, $this->userRepository, false, $updatedVars)->delay(now()->addSeconds(5));
                    }

                    return;
                }

                if ($modelChanged) {
                    WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));
                }

                return;
            }

            if($citiesToNotify) {
                $originalEventCities = json_decode($originalEvent->getAttributes()['cities_to_notify']);
                $eventCities = json_decode($event->getAttributes()['cities_to_notify']);
                $newCities = array_diff($eventCities, $originalEventCities);

                if (count($newCities)) {
                    $oldEmails = array_diff($eventCities, $newCities);
                    $updatedVars['cities'] = $oldEmails;

                    if ($modelChanged) {
                        WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));
                    }

                    if (count($eventCities) >= count($originalEventCities)) {
                        $updatedVars['cities'] = $newCities;

                        WebClassJob::dispatch($event, $this->userRepository, false, $updatedVars)->delay(now()->addSeconds(5));
                    }

                    return;
                }

                if ($modelChanged) {
                    WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));
                }

                return;
            }
        }

        if ($modelChanged) {
            WebClassJob::dispatch($event, $this->userRepository, $modelChanged, $updatedVars)->delay(now()->addSeconds(5));
        }
    }

    private function defineNameAndStartAtVariables($val, $originalEvent, $event): array
    {
        if ($val === 'name' || $val === 'start_at') {
                return [
                    "original_{$val}" => ($val === 'start_at') ? $originalEvent->start_at_datetime_formatted : $originalEvent->getAttributes()[$val],
                    "updated_{$val}" => ($val === 'start_at') ? $event->start_at_datetime_formatted : $event->getAttributes()[$val]
                ];
        }

        return [];
    }
}
