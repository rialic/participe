<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Certificate\PrintCertificateRequest;
use App\Http\Requests\Certificate\IndexCertificateRequest;
use App\Http\Resources\CertificateResource;
use App\ServiceLayer\CertificateServiceLayer;
use App\Traits\HasControllerResource;

class CertificateController extends Controller
{
    use HasControllerResource;

    public function __construct(
        protected readonly CertificateServiceLayer $service,
        protected readonly string $resourceCollection = CertificateResource::class
    ) {
        $this->indexValidatorRequest = IndexCertificateRequest::class;
    }

    public function print(PrintCertificateRequest $request)
    {
        try {
            $data = $request->validated();

            return $this->service->print($data);
          } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }
}
