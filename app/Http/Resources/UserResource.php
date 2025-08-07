<?php

namespace App\Http\Resources;

use App\Traits\HasJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use HasJsonResource;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->model = 'Usuário';
    }

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
            'email' => $this->email,
            'abilities' => $this->roles->isNotEmpty() ? $this->roles->map(fn ($role) => $role->permissions->map(fn ($permission) => $permission->name))->flatten() : null
        ];
    }
}
