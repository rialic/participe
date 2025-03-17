<?php

namespace App\ServiceLayer;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\ServiceLayer\Base\ServiceResource;

class EventServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly UserRepository $userRepository
    )
    {
        $this->repository = $eventRepository;
    }

    public function syncParticipants($data): object
    {
        $event = $this->repository->findByUuid($data['uuid']);
        $userList = $this->userRepository->getModel()->whereIn('uuid', $data['participants'])->get()->pluck('id')->all();

        $event->participants()->syncWithoutDetaching($userList);

        return $event;
    }

    public function storeParticipantRating($data)
    {
        $event = $this->repository->findByUuid($data['event_uuid']);
        $user = $this->userRepository->findByUuid($data['participant']);

        $event->participants()->updateExistingPivot($user->id, [
            'rating_event' => $data['rating_event'],
            'rating_event_schedule' => $data['rating_event_schedule'],
            'hint' => $data['hint'],
            'rated_at' => now(),
        ]);

        return $event;
    }
}