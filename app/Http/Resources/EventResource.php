<?php

namespace App\Http\Resources;

use App\Traits\HasJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    use HasJsonResource;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->model = 'Evento';
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'start_at' => $this->start_at,
            'start_minutes_additions' => $this->start_minutes_additions,
            'end_at' => $this->end_at,
            'end_minutes_additions' => $this->end_minutes_additions,
            'organisation' => $this->organisation,
            'room_link' => $this->room_link,
            'workload' => $this->workload
        ];
    }
}
