<?php

namespace App\Http\Resources;

use App\Traits\HasJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    use HasJsonResource;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->model = 'Participante';
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource || !$this->uuid) {
            return [];
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'sex' => $this->sex,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'establishment' => $this->establishments->isNotEmpty() ? $this->whenLoaded('establishments', fn() => [
                'uuid' => $this->establishments->first()?->uuid,
                'city' => [
                    'uuid' => $this->establishments->first()?->city->uuid,
                    'state' => [
                        'uuid' => $this->establishments->first()?->city->state->uuid
                    ]
                ]
            ]) : null,
            'cbo' => $this->whenLoaded('establishments', fn() => [
                'uuid' => $this->establishments->first()?->pivot->cbo->uuid,
            ])
        ];
    }
}
