<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\User\SendMagicLinkRequest;
use App\Http\Requests\User\UpSertUserRequest;
use App\Http\Resources\UserResource;
use App\ServiceLayer\UserServiceLayer;
use App\Traits\HasControllerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    use HasControllerResource;

    public function __construct(
        private readonly UserServiceLayer $service,
        private readonly string $resourceCollection = UserResource::class
    ) {
        $this->storeValidatorRequest = UpSertUserRequest::class;
        $this->updateValidatorRequest = UpSertUserRequest::class;
    }

    public function me(): JsonResource|JsonResponse
    {
        try {
            $data = auth()?->user()->load('roles');
            $data = $this->service->me($data);

            return new $this->resourceCollection($data);
        } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }

    public function sendMagicLink(SendMagicLinkRequest $request): JsonResource|JsonResponse
    {
        try {
            $data = $request->validated();
            $this->service->sendMagicLink($data);

            return response()->json(['status' => 200, 'data' => null, 'message' => 'Verifique seu email com o link mágico de login.']);
        } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }
}
