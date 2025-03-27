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
        $user = auth()->user();

        return response()->json([
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
