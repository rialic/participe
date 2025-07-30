<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Resources\EventReportResource;
use App\ServiceLayer\EventReportServiceLayer;
use Illuminate\Http\Request;

class EventReportController extends Controller
{
    public function __construct(
        protected readonly EventReportServiceLayer $service,
        protected readonly string $resourceCollection = EventReportResource::class
    ){
        $this->filterFields = ['name', 'start_at_begin', 'start_at_end', 'participant', 'cbo', 'organization', 'desc_bireme', 'state', 'city', 'macro_zone', 'micro_zone'];
    }

    public function print(Request $request)
    {
        $uri = request()->route()->uri();
        $type = substr($uri, strrpos($uri, '/') + 1, strlen($uri));
        $method = "{$type}Print";
        try {
            $data = $request->only(array_merge($this->filterFields, ['limit', 'order_by', 'direction']));

            return $this->service->$method($data);
          } catch (\Exception $e) {
            return ApiException::handleException($e, func_get_args());
        }
    }
}
