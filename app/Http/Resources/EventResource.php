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
        $method = $request->method();

        if ($method === 'DELETE') {
            return [];
        }

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'desc_bireme' => $this->descs->isNotEmpty() ? $this->whenLoaded('descs', $this->descs->map(fn($bireme) => [
                'uuid' => $bireme->uuid,
                'bireme_code' => $bireme->bireme_code
            ])) : null,
            'start_at' => $this->start_at,
            'start_minutes_additions' => $this->start_minutes_additions,
            'end_at' => $this->end_at,
            'end_minutes_additions' => $this->end_minutes_additions,
            'organization' => $this->organization,
            'room_link' => $this->room_link,
            'workload' => $this->workload,
            'type_notification' => $this->type_notification,
            'summary_emails' => $this->summary_emails,
            'select_group_emails' => $this->select_group_emails,
            'cities_to_notify' => $this->cities_to_notify,
            'attachment' => $this->attachment?->exists() ? $this->whenLoaded('attachment', [
                'name' => $this->attachment->original_name,
                'mime' => $this->attachment->mime,
                'file' => base64_encode(file_get_contents(storage_path("app/private/{$this->attachment->path}")))
            ]) : null,
            'created_by' => $this->whenLoaded('createdBy', [
                'uuid' => $this->createdBy->uuid,
                'name' => $this->createdBy->name,
            ]),
            'created_at_datetime_formatted' => $this->created_at_datetime_formatted,
            'updated_at_datetime_formatted' => $this->updated_at_datetime_formatted
        ];
    }
}
