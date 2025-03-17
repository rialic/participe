<?php

namespace App\Http\Resources;

use App\Traits\HasJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    use HasJsonResource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource || $this->events->isEmpty() ) {
            return [];
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'events' => $this->whenLoaded('events', $this->events->map(fn($event) => [
                'uuid' => $event->uuid,
                'name' => $event->name,
                'start_at' => $event->start_at,
                'end_at' => $event->end_at,
                'rated_at' => $event->pivot->rated_at,
            ]))
        ];
    }
}
