<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\ServiceLayer\DashboardServiceLayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardServiceLayer $service,
        private readonly string $resourceCollection = DashboardResource::class
    )
    {}

    public function __invoke(): JsonResource|JsonResponse
    {
        $data = $this->service->dashboard();

        return new $this->resourceCollection($data);
    }
}
