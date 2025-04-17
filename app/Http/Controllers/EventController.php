<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\StoreParticipantRatingRequest;
use App\Http\Requests\Event\SyncParticipantsRequest;
use App\Http\Resources\EventResource;
use App\ServiceLayer\EventServiceLayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller
{
    public function __construct(
        protected readonly EventServiceLayer $service,
        protected readonly string $resourceCollection = EventResource::class
    ){
        $this->filterFields = ['typeEvent', 'name', 'startAt', 'endAt', 'organization', 'biremeCode', 'eventsAvailables'];
        $this->storeValidatorRequest = StoreEventRequest::class;
    }

    public function syncParticipants(SyncParticipantsRequest $request): JsonResource|JsonResponse
    {
        try {
            $data = $request->validated();
            $model = $this->service->syncParticipants($data);

            return (new $this->resourceCollection($model))->response()->setStatusCode(201);
        } catch (\Exception $e) {
        return ApiException::handleException($e, func_get_args());
        }
    }

    public function storeParticipantRating(StoreParticipantRatingRequest $request)
    {
        try {
            $data = $request->validated();
            $model = $this->service->storeParticipantRating($data);

            return (new $this->resourceCollection($model))->response()->setStatusCode(201);
        } catch (\Exception $e) {
        return ApiException::handleException($e, func_get_args());
        }
    }
}
