<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'start_at' => $this->start_at_datetime_formatted,
            'end_at' => $this->end_at_datetime_formatted,
            'organization' => $this->organization,
            'descs' => $this->descs,
            'participant' => $this->participant,
            'signed_up_at' => $this->signed_up_at,
            'rated_at' => $this->rated_at,
            'rating_event' => $this->rating_event,
            'rating_event_schedule' => $this->rating_event_schedule,
            'hint' => $this->hint,
            'cbo' => $this->cbo,
            'state' => $this->state,
            'city' => $this->city,
            'macro_zone' => $this->macro_zone,
            'micro_zone' => $this->micro_zone
        ];
    }
}
