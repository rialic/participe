<?php

namespace App\ServiceLayer;

use App\Repository\DescsRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\ServiceLayer\Base\ServiceResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventServiceLayer extends ServiceResource
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly UserRepository $userRepository,
        private readonly DescsRepository $descsRepository
    )
    {
        $this->repository = $eventRepository;
    }

    public function store($data): object
    {
        $data['workload'] = (int) Carbon::parse($data['start_at'])->diffInMinutes($data['end_at']);
        $data['created_by'] = auth()->user()->id;
        $data['descs'] = collect($data['descs'])->map(fn($biremeCode) => $this->descsRepository->getUuidToId($biremeCode)->id)->all();

        return DB::transaction(function() use($data) {
            $event = parent::store($data);

            $event->descs()->sync($data['descs']);

            return $event;
        });
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