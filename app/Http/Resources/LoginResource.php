<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResource implements LoginResponseContract
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toResponse($request)
    {
        $user = auth()->user()->load('roles');

        return response()->json([
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'abilities' => $user->roles->isNotEmpty() ? $user->roles->map(fn ($role) => $role->permissions->map(fn ($permission) => $permission->name))->flatten() : null
        ]);
    }
}
