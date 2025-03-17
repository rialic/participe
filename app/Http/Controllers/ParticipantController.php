<?php

namespace App\Http\Controllers;

use App\Http\Requests\Participant\ShowParticipantRequest;
use App\Http\Requests\Participant\StoreUpdateParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\ServiceLayer\ParticipantServiceLayer;

class ParticipantController extends Controller
{
    public function __construct(
        protected readonly ParticipantServiceLayer $service,
        protected readonly string $resourceCollection = ParticipantResource::class
    )
    {
        $this->showValidatorRequest = ShowParticipantRequest::class;
        $this->storeValidatorRequest = StoreUpdateParticipantRequest::class;
        $this->updateValidatorRequest = StoreUpdateParticipantRequest::class;
    }
}
