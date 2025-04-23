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
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'participant' => [
                'uuid' => $this->participants->first()->uuid,
                'name' => $this->participants->first()->name
            ]
        ];
    }
}
