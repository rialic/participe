<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentResource extends JsonResource
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
            'cnes' => $this->cnes,
            'city' => $this->whenLoaded('city', fn() => [
                'uuid' => $this->city->uuid,
                'name' => $this->city->name,
                'state' => $this->whenLoaded('state', fn() => [
                    'uuid' => $this->state->uuid,
                    'name' => $this->state->name,
                    'acronym' => $this->state->acronym,
                ])
            ])
        ];
    }
}
