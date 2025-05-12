<?php

namespace App\ServiceLayer;

use App\Repository\Interfaces\DescsInterface as DescsRepository;
use App\Repository\Interfaces\EventInterface as EventRepository;
use App\Repository\Interfaces\UserInterface as UserRepository;
use App\ServiceLayer\Base\ServiceResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function store($data, ?object $model = null): object
    {
        $data['descs'] = collect($data['desc_bireme'])->map(fn($biremeCode) => $this->descsRepository->getUuidToId($biremeCode)->id)->all();
        $data['summary_emails'] = $data['summary_emails'] ?? null;
        $data['cities_to_notify'] = $data['cities_to_notify'] ?? null;
        $data['select_group_emails'] = $data['select_group_emails'] ?? null;
        $data['workload'] = (int) Carbon::parse($data['start_at'])->diffInMinutes($data['end_at']);

        return DB::transaction(function() use($data, $model) {
            $event = parent::store($data, $model);
            $event->descs()->sync($data['descs']);

            if (isset(optional($data)['banner'])) {
                $fileName = optional($data)['banner']->hashName();

                if ($event->attachment) {
                    Storage::disk('local')->delete($event->attachment->path);
                    $event->attachment->delete();
                }

                $event->attachment()->create([
                    'name' => substr($fileName, 0, strpos($fileName, '.')),
                    'original_name' => $data['banner']->getClientOriginalName(),
                    'path' => Storage::putFileAs('attachments', $data['banner'], $fileName),
                    'mime' => $data['banner']->getClientMimeType()
                ]);
                $event->load('attachment');
            }

            return $event;
        });
    }

    public function update(string $uuid, array $data): object
    {
        $event = $this->repository->findByUuid($uuid);
        $originalEvent = clone $event;

        app()->instance("original_event_{$event->id}", $originalEvent);
        return $this->store($data, $event);
    }

    public function delete(string $uuid): bool
    {
        $event = $this->repository->findByUuid($uuid)->load('descs', 'participants');
        $descsIds = $event->descs->pluck('id')->toArray();
        $participantsIds = $event->participants->pluck('id')->toArray();
        $response = parent::delete($uuid);

        DB::transaction(function() use($event, $descsIds, $participantsIds) {
            $event->descs()->syncWithPivotValues($descsIds, ['deleted_at' => now(), 'deleted_by' => auth()->user()->id]);
            $event->participants()->syncWithPivotValues($participantsIds, ['deleted_at' => now(), 'deleted_by' => auth()->user()->id]);
        });

        return $response;
    }

    public function syncParticipants($data): object
    {
        $event = $this->repository->findByUuid($data['uuid']);
        $userList = $this->userRepository->getModel()->whereIn('uuid', $data['participants'])->get()->pluck('id')->all();

        return DB::transaction(function() use($event, $userList) {
            $event->participants()->syncWithoutDetaching($userList);

            return $event;
        });
    }

    public function storeParticipantRating($data)
    {
        $event = $this->repository->findByUuid($data['event_uuid']);
        $user = $this->userRepository->findByUuid($data['participant']);

        return DB::transaction(function() use($data, $event, $user) {
            $event->participants()->updateExistingPivot($user->id, [
                'rating_event' => $data['rating_event'],
                'rating_event_schedule' => $data['rating_event_schedule'],
                'hint' => $data['hint'],
                'rated_at' => now(),
            ]);

            return $event;
        });
    }
}