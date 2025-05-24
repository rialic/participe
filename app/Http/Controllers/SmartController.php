<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\SmartResource;
use App\ServiceLayer\SmartServiceLayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmartController extends Controller
{
    public function __construct(
        protected readonly SmartServiceLayer $service,
        protected readonly string $resourceCollection = SmartResource::class
    ) {
        $this->filterFields = ['scope_search_establishment', 'scope_search_professionals', 'scope_search_webs'];
    }

    public function sendEstablishments(Request $request): JsonResource|JsonResponse
    {
        try {
            $data = $request->only(['dispatch_type', 'custom_date', 'exclude_tag_list', 'include_tag_list']);

            return $this->service->sendEstablishments($data);
        } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }

    public function sendProfessionals(Request $request): JsonResource|JsonResponse
    {
        try {
            $data = $request->only(['dispatch_type', 'custom_date', 'include_tag_list']);

            return $this->service->sendProfessionals($data);
        } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }

    public function sendWebs(Request $request): JsonResource|JsonResponse
    {
        try {
            $data = $request->only(['dispatch_type', 'custom_date']);

            return $this->service->sendWebs($data);
        } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }
}
